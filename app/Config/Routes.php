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

   // Core Shipment
      //Index Page
      $routes->get('shipment', 'Shipment::index', ['filter' => 'tokenAuth']);

      // Form Add New Shipment
      $routes->get('shipment/add', 'Shipment::form_add', ['filter' => 'tokenAuth']);

      // Form Process Shipment
      $routes->get('shipment/process/(:num)', 'Shipment::process/$1', ['filter' => 'tokenAuth']);

      // API For Add New Shipment
      $routes->post('shipment/api/add', 'Shipment::add', ['filter' => 'tokenAuth']);

      // Make Set Paid
      $routes->get('shipment/paid/(:num)', 'Shipment::setPaid/$1');

      // Make Set Arrived
      $routes->get('shipment/arrived/(:num)', 'Shipment::setArrived/$1');

       // Make Set Delivered
      $routes->get('shipment/deliver/(:num)', 'Shipment::setDelivery/$1');
      $routes->post('shipment/deliverycustomer/(:num)', 'Shipment::saveDeliveryCustomer/$1');


      // Core Invoice
      $routes->get('invoice/pdf/(:num)', 'Invoice::exportPdf/$1');
