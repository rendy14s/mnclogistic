<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 // Routes Pages
    $routes->get('/', 'Home::index');

    $routes->get('admin', 'Admin::index');

    $routes->get('login', 'Auth::login');

    $routes->get('logout', 'Auth::logout');

    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

    $routes->get('register', 'Register::index', ['filter' => 'auth']);

    $routes->get('users', 'Users::index', ['filter' => 'auth']);

 // Routes API


    $routes->post('api/loginPost', 'Auth::loginPost');
