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
