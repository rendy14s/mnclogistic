<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 // Get Page

    $routes->get('/', 'Home::index');

    $routes->get('login', 'Auth::login');

    $routes->get('logout', 'Auth::logout');

    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'tokenAuth']);

    $routes->get('register', 'Register::index', ['filter' => 'tokenAuth']);

    $routes->get('users', 'Users::index', ['filter' => 'tokenAuth']);

 // API
    $routes->post('auth/loginPost', 'Auth::loginPost');
