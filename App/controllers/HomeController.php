<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    } //end of __construct() 
    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings LIMIT 6')->fetchAll();
        loadView('home', [
            'listings' => $listings
        ]);
    } //end of index()
}
