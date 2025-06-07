<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
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

        $newListingData['user_id'] = 1;

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

        if (!$listing) {
            ErrorController::notFound404('Listing not found!');
            return;
        }

        $this->db->query('DELETE FROM listings WHERE id =:id', $params);

        //Set flash messages for delete confirmation
        $_SESSION['success_message'] = 'Listing deleted successfully';

        redirect('/listings');
    } //end of destroy($params)
}
//explode will take a string and turn it into an array; implode will take an array and turn it into a string