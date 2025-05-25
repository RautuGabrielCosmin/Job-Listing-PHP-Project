<?php

$routes = require basePath("routes.php");

if (array_key_exists($uri, $routes)) { //if the route exists return the uri for that route
    require(basePath($routes[$uri]));
} else { //if the route does NOT exists return error '404'
    http_response_code(404);
    require basePath($routes['404']);
}
