<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('admin', 'Admin::index');

$routes->get('dashboard', 'Dashboard::index');

$routes->get('login', 'Login::index');