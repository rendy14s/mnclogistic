<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('admin', 'Admin::index');

$routes->get('login', 'Auth::login');

$routes->post('auth/loginPost', 'Auth::loginPost');

$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->get('register', 'Register::index', ['filter' => 'auth']);

$routes->get('users', 'Users::index', ['filter' => 'auth']);