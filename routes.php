<?php

$router->get('/', 'HomeController@index');

$router->get('/listings', 'ListingController@index');
$router->post('/listings', 'ListingController@store');

$router->get('/listings/create', 'ListingController@create');

$router->get('/listings/edit/{id}', 'ListingController@edit');
$router->get('/listings/{id}', 'ListingController@show');
$router->put('/listings/{id}', 'ListingController@update');
$router->delete('/listings/{id}', 'ListingController@destroy');

$router->get('/auth/register', 'UserController@create');
$router->get('/auth/login', 'UserController@login');

$router->post('/auth/register', 'Usercontroller@store');
