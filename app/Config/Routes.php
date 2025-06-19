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

   // API
    $routes->post('auth/loginPost', 'Auth::loginPost');


   // Users
      // Index Page
      $routes->get('users', 'Users::index', ['filter' => 'tokenAuth']);

      // Form Add Register User
      $routes->get('users/register', 'Users::form_add', ['filter' => 'tokenAuth']);

      // API For Add User Register
      $routes->post('users/api/register', 'Users::create', ['filter' => 'tokenAuth']);

   // Customer
      // Index Page
      $routes->get('customers', 'Customers::index', ['filter' => 'tokenAuth']);

      // Form Add Register Customer
      $routes->get('customers/register', 'Customers::form_add', ['filter' => 'tokenAuth']);

      // API For Add Customer Register
      $routes->post('customers/api/register', 'Customers::create', ['filter' => 'tokenAuth']);

   // Price
      // Index Page
      $routes->get('prices', 'Price::index', ['filter' => 'tokenAuth']);

      // Form Add Register Customer
      $routes->get('prices/register', 'Price::form_add', ['filter' => 'tokenAuth']);

      // API For Add Customer Register
      $routes->post('prices/api/register', 'Price::create', ['filter' => 'tokenAuth']);

   // 3rd Courier
      // Index Page
      $routes->get('3rdcourier', 'Courier::index', ['filter' => 'tokenAuth']);

      // Form Add 3rd Courier
      $routes->get('3rdcourier/register', 'Courier::form_add', ['filter' => 'tokenAuth']);

      // API For Add 3rd Courier
      $routes->post('3rdcourier/api/register', 'Courier::create', ['filter' => 'tokenAuth']);

   // Core Shippment
      //Index Page
      $routes->get('shippment', 'Shippment::index', ['filter' => 'tokenAuth']);

      // Form Add New Shippment
      $routes->get('shippment/add', 'Shippment::form_add', ['filter' => 'tokenAuth']);

      // Form Process Shippment
      $routes->get('shippment/process/(:num)', 'Shippment::process/$1', ['filter' => 'tokenAuth']);

      // API For Add New Shippment
      $routes->post('shippment/api/add', 'Shippment::add', ['filter' => 'tokenAuth']);
