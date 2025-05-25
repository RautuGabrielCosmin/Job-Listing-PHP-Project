<?php
// require basePath('/views/home.view.php'); refactored with ->
require '../helpers.php';

//loadView('home'); refactored with ->
$routes = [
    '/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listing/creating.php',
    '404' => 'controllers/error/404.php'
];

$uri = $_SERVER['REQUEST_URI'];

if (array_key_exists($uri, $routes)) { //if the route exists return the uri for that route
    require(basePath($routes[$uri]));
} else { //if the route does NOT exists return error '404'
    require basePath($routes['404']);
}
