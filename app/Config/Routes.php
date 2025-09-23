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

      // Form Edit User
      $routes->get('users/edit/(:num)', 'Users::edit/$1', ['filter' => 'tokenAuth']);

      // Form Change Password
      $routes->get('users/change_password/(:num)', 'Users::changePassword/$1', ['filter' => 'tokenAuth']);
      

      // API For Add User Register
      $routes->post('users/api/register', 'Users::create', ['filter' => 'tokenAuth']);

      // API For Edit User
      $routes->post('users/api/edit/(:num)', 'Users::edit/$1', ['filter' => 'tokenAuth']);

      // API For Change Password
      $routes->post('users/api/changePassword/(:num)', 'Users::changePassword/$1', ['filter' => 'tokenAuth']);

      // API For Soft Delete User
      $routes->get('users/softDelete/(:num)', 'Users::softDelete/$1', ['filter' => 'tokenAuth']);

   // Customer
      // Index Page
      $routes->get('customers', 'Customers::index', ['filter' => 'tokenAuth']);

      // Form Add Register Customer
      $routes->get('customers/register', 'Customers::form_add', ['filter' => 'tokenAuth']);

      // From Add Pricing Customer
      $routes->get('customers/register/pricing', 'Customers::form_add_pricing', ['filter' => 'tokenAuth']);

      // // Form Edit Customer
      $routes->get('customers/edit/(:num)', 'Customers::edit/$1', ['filter' => 'tokenAuth']);

      // Form Edit Pricing Customer
      $routes->get('customers/edit/pricing/(:num)', 'Customers::pricing/$1', ['filter' => 'tokenAuth']);

      // API For Add Customer Register
      $routes->post('customers/register/api/add', 'Customers::create', ['filter' => 'tokenAuth']);

      // API For Add Pricing Customer
      $routes->post('customers/api/edit/pricing', 'Customers::editPricing', ['filter' => 'tokenAuth']);

      // API For Edit Customer
      $routes->post('customers/api/edit/(:num)', 'Customers::edit/$1', ['filter' => 'tokenAuth']); 

      //API For Soft Delete Customer
      $routes->get('customers/softDelete/(:num)', 'Customers::softDelete/$1', ['filter' => 'tokenAuth']);

   // 3rd Courier
      // Index Page
      $routes->get('thirdcourier', 'Courier::index', ['filter' => 'tokenAuth']);

      // Form Add 3rd Courier
      $routes->get('thirdcourier/register', 'Courier::form_add', ['filter' => 'tokenAuth']);

      // Form Edit 3rd Courier
      $routes->get('thirdcourier/edit/(:num)', 'Courier::edit/$1', ['filter' => 'tokenAuth']);

      // API For Add 3rd Courier
      $routes->post('thirdcourier/api/register', 'Courier::create', ['filter' => 'tokenAuth']);

      // API For Edit Courier
      $routes->post('thirdcourier/api/edit/(:num)', 'Courier::edit/$1', ['filter' => 'tokenAuth']);

      // API For Soft Delete User
      $routes->get('thirdcourier/softDelete/(:num)', 'Courier::softDelete/$1', ['filter' => 'tokenAuth']);

   // Core Shipment
      //Index Page
      $routes->get('shipment', 'Shipment::index', ['filter' => 'tokenAuth']);

      // Form Add New Shipment
      $routes->get('shipment/add', 'Shipment::form_add', ['filter' => 'tokenAuth']);

      // Form Process Shipment
      $routes->get('shipment/process/(:num)', 'Shipment::process/$1', ['filter' => 'tokenAuth']);

      // Form Edit Shipment
      $routes->get('shipment/edit/(:num)', 'Shipment::form_edit/$1', ['filter' => 'tokenAuth']);

      // API For Edit Shipment
      $routes->post('shipment/api/edit/(:num)', 'Shipment::edit/$1', ['filter' => 'tokenAuth']);

      // API For Add New Shipment
      $routes->post('shipment/api/add', 'Shipment::add', ['filter' => 'tokenAuth']);

      // Api For Render / Get Customer Price
      $routes->get('shipment/api/getCustomerPrice/(:num)', 'Shipment::getCustomerPrice/$1', ['filter' => 'tokenAuth']);

      // Make Set Paid
      $routes->get('shipment/paid/(:num)', 'Shipment::setPaid/$1');

      // Make Set Arrived
      $routes->get('shipment/arrived/(:num)', 'Shipment::setArrived/$1');

       // Make Set Delivered
      $routes->get('shipment/deliver/(:num)', 'Shipment::setDelivery/$1');
      $routes->post('shipment/deliverycustomer/(:num)', 'Shipment::saveDeliveryCustomer/$1');

      // API For Bulk Sending Shipment
      $routes->post('shipment/api/bulkSending', 'Shipment::bulkSending', ['filter' => 'tokenAuth']);

      // API For List Shipment
      $routes->get('shipment/api/list', 'Shipment::list', ['filter' => 'tokenAuth']);

      // API For Data Shipment
      $routes->get('shipment/api/datashipment', 'Shipment::datashipment', ['filter' => 'tokenAuth']);

      // API For Delete Shipment
      $routes->get('shipment/api/delete/(:num)', 'Shipment::delete/$1', ['filter' => 'tokenAuth']);


   // Core Invoice
      $routes->get('invoice/pdf/(:num)', 'Invoice::exportPdf/$1');

   // Core Reports
      $routes->get('reports/way-bill', 'Reports::Waybill', ['filter' => 'tokenAuth']);

      // API For Generate Way Bill
      $routes->post('reports/way-bill/generatePdf', 'Reports::GenerateWayBill', ['filter' => 'tokenAuth']); 
