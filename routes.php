<?php

$router->get('/', 'HomeController@index');

$router->get('/listings', 'ListingController@index');
$router->post('/listings', 'ListingController@store', ['auth']);

$router->get('/listings/create', 'ListingController@create', ['auth']);

$router->get('/listings/edit/{id}', 'ListingController@edit', ['auth']);
$router->get('/listings/search', 'ListingController@search');
$router->get('/listings/{id}', 'ListingController@show');
$router->put('/listings/{id}', 'ListingController@update', ['auth']);
$router->delete('/listings/{id}', 'ListingController@destroy', ['auth']);

$router->get('/auth/register', 'UserController@create', ['guest']);
$router->post('/auth/register', 'Usercontroller@store', ['guest']);

$router->get('/auth/login', 'UserController@login', ['guest']);
$router->post('/auth/login', 'Usercontroller@authenticate');

$router->post('/auth/logout', 'Usercontroller@logout', ['auth']);
