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

    // Posts routes - admin and writer can access
    $routes->group('posts', ['filter' => 'role:admin,writer'], function ($routes) {
        $routes->get('/', 'Admin\Posts::index');
        $routes->get('create', 'Admin\Posts::create');
        $routes->post('store', 'Admin\Posts::store');
        $routes->get('edit/(:num)', 'Admin\Posts::edit/$1');
        $routes->post('update/(:num)', 'Admin\Posts::update/$1');
        $routes->put('update/(:num)', 'Admin\Posts::update/$1');
        $routes->post('delete/(:num)', 'Admin\Posts::delete/$1');
        $routes->delete('delete/(:num)', 'Admin\Posts::delete/$1');
    });

    // Users routes - admin only
    $routes->group('users', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->get('create', 'Admin\Users::create');
        $routes->post('store', 'Admin\Users::store');
        $routes->get('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('update/(:num)', 'Admin\Users::update/$1');
        $routes->put('update/(:num)', 'Admin\Users::update/$1');
        $routes->post('delete/(:num)', 'Admin\Users::delete/$1');
        $routes->delete('delete/(:num)', 'Admin\Users::delete/$1');
    });
});
