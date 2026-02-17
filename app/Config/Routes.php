<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/render/image/(:any)', 'Render::image/$1');
$routes->post('/api/login', 'Login::index');
$routes->get('/api/customers', 'Customer::listCustomers');
$routes->post('/api/customers', 'Customer::addCustomer');
$routes->get('/api/customers-cari/(:any)', 'Customer::cariCustomers/$1');
$routes->get('/api/visit/by-user-to-day', 'Visit::byUserToDay');
$routes->get('/api/visit/by-user/(:any)/(:any)', 'Visit::byUser/$1/$2');
$routes->post('/api/visits', 'Visit::addVisit');
