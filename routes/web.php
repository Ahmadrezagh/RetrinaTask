<?php

// Home routes
$app->router()->get('/', 'HomeController@index');
$app->router()->get('/home', 'HomeController@index');
$app->router()->get('/about', 'HomeController@about');

// User routes with parameters
$app->router()->get('/user/{id}', 'HomeController@user');

// API routes
$app->router()->get('/api', 'HomeController@api');

// Example closure route
$app->router()->get('/hello/{name}', function($name) {
    echo "Hello, {$name}!";
});

// Example POST route
$app->router()->post('/contact', function() {
    echo "Contact form submitted!";
}); 