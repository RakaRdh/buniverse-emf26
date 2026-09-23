<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->post('api/track-ticket', 'Home::trackTicket');

// Media Proxy Route under Frontend domain (GET & HEAD)
$routes->match(['get', 'head'], 'uploads/(:any)', 'MediaController::serve/$1');
