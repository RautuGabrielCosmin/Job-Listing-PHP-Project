<?php
require __DIR__ . '/../vendor/autoload.php'; //compose vendor
require '../helpers.php';

use Framework\Router;

// require basePath('Framework/Router.php');
// require basePath('Framework/Database.php');

//loader of the class/classes without require/ SWITCHED WITH COMPOSER
// spl_autoload_register(function ($class) {
//     $path = basePath('Framework/' . $class . '.php');
//     if (file_exists($path)) {
//         require $path;
//     }
// });

//Instatiate the router
$router = new Router();

//Get the routes
$routes = require basePath('routes.php');

//Get the current uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// inspectAndDie($uri);
$method = $_SERVER['REQUEST_METHOD'];

//Route the request
$router->route($uri, $method);

// inspectAndDie($method);
// inspectAndDie($uri);
