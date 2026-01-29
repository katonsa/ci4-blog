<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Public blog routes
$routes->get('post/(:segment)', 'Home::view/$1');

// Auth routes
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// Admin routes (protected by auth filter)
$routes->group('/admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    // Posts routes
    $routes->group('posts', function ($routes) {
        $routes->get('/', 'Admin\Posts::index');
        $routes->get('create', 'Admin\Posts::create');
        $routes->post('store', 'Admin\Posts::store');
        $routes->get('edit/(:num)', 'Admin\Posts::edit/$1');
        $routes->post('update/(:num)', 'Admin\Posts::update/$1');
        $routes->get('delete/(:num)', 'Admin\Posts::delete/$1');
    });
});
