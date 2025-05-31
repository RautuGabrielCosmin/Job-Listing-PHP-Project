<?php

namespace App\Controllers;

use Framework\Database;

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
        loadView('home', [
            'listings' => $listings
        ]);
    } //end of index()

    public function create()
    {
        loadView('listings/create');
    }

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
    }
}
