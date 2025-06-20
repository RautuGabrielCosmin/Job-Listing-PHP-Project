<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class ListingController
{
    /**
     * Constructor
     * @var 
     */
    protected $db;
    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    } //end of __construct() 
    /**
     * Fetching the index from the URI for the Rsouter
     * @return void
     */
    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();
        loadView('listings/index', [
            'listings' => $listings
        ]);
    } //end of index()

    public function create()
    {
        loadView('listings/create');
    } //end of create()

    /**
     * Show a single listing job
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings Where id = :id', $params)->fetch();

        //check if listing exists
        if (!$listing) {
            ErrorController::notFound404();
            return; // ← If you stop here (return), only the error page is sent.
        }

        // But if you forget to return, PHP continues into your normal layout,
        // tries to include <head> partials, CSS links, etc., and that conflicts
        // with the (somewhat standalone) HTML you already output in notFound404().
        loadView('listings/show', [
            'listing' => $listing
        ]);
    } //end of show($params)

    /**
     * Store data in database
     * 
     * @return void
     */
    public function store()
    {
        //it is NOT an associative array those are not keys, those are the numeric keys
        $allowedFields = [
            'title', //0
            'description', //1
            'salary', //2
            'tags', //...
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];
        //array_flip turns the keys into the values and the values into the keys it reverses the values into the keys that
        //will match the data that is inputed
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = Session::get('user')['id'];

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'email', 'city', 'state', 'phone', 'salary'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        if (!empty($errors)) {
            //Reload view with errors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else { //Sumbit data
            $field = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $fields = implode(',', $fields);

            $values = [];

            foreach ($newListingData as $field => $value) {
                //Convert empty strings to null
                if ($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }
            $values = implode(',', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            Session::setFlashMessage('success_message', 'Listing created successfully');

            redirect('/listings');
        }
    } //end of store()

    /**
     * Delete a listing job
     * 
     * @param array $params
     * @return void
     */
    public function destroy($params)
    {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id =:id', $params)->fetch();

        //Check if listing exists
        if (!$listing) {
            ErrorController::notFound404('Listing not found!');
            return;
        }

        // Authorization
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized');
            return redirect('/listings/' . $listing->id);
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        //Set flash messages for delete confirmation
        Session::setFlashMessage('success_message', 'Listing deleted successfully');

        redirect('/listings');
    } //end of destroy($params)

    /**
     * Show the listing edit form
     * 
     * @param array $params
     * @return void
     */
    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id =:id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound404('Listing not found!');
            return;
        }

        loadView('listings/edit', ['listing' => $listing]);
    }

    /**
     * Update a listing
     * 
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id=:id', $params)->fetch();

        //Check if the listing exists
        if (!$listing) {
            ErrorController::notFound404('Listing not found!');
            return;
        }

        // Authorization
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = [
            'title', //0
            'description', //1
            'salary', //2
            'tags', //...
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        } else {
            //Submit to database
            // inspectAndDie('Success');
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }
            $updateFields = implode(", ", $updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id=:id";

            $updateValues['id'] = $id;

            $this->db->query($updateQuery, $updateValues);

            Session::setFlashMessage('success_message', 'Listing updated successfully');

            redirect('/listings/' . $id);
        }
    }

    /**
     * Search listings by keywords/location
     * @return void
     */
    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND
        (city LIKE :location OR state LIKE :location)";

        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $params)->fetchAll();

        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}
//explode will take a string and turn it into an array; implode will take an array and turn it into a string