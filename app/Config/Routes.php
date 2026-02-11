<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/api/login', 'Login::index');
$routes->get('/api/visit/by-user/(:any)/(:any)', 'Visit::byUser/$1/$2');
$routes->get('/api/visit/by-user-to-day', 'Visit::byUserToDay');
$routes->get('/api/customers', 'Customer::listCustomers');
$routes->post('/api/customers', 'Customer::addCustomer');
$routes->get('/api/customers-cari/(:any)', 'Customer::cariCustomers/$1');
