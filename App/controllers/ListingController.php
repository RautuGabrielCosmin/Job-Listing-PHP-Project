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

    public function show()
    {
        $id = $_GET['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings Where id = :id', $params)->fetch();

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }
}
