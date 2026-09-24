<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->post('api/track-ticket', 'Home::trackTicket');

// Q&A Routes
$routes->get('qna/dashboard', 'QnaController::dashboard');
$routes->get('qna/dashboards', 'QnaController::dashboard');
$routes->get('qna/api/list', 'QnaController::apiList');
$routes->post('qna/submit', 'QnaController::submit');
$routes->get('qna/(:num)/(:num)', 'QnaController::daySession/$1/$2');
$routes->get('qna/(:num)', 'QnaController::sessionOnly/$1');
$routes->get('qna/day/(:num)/session/(:num)', 'QnaController::daySession/$1/$2');
$routes->get('qna/day=(:num)&session=(:num)', 'QnaController::daySession/$1/$2');
$routes->get('qna/session/(:num)', 'QnaController::sessionOnly/$1');
$routes->get('qna', 'QnaController::index');

// Media Proxy Route under Frontend domain (GET & HEAD)
$routes->match(['get', 'head'], 'uploads/(:any)', 'MediaController::serve/$1');
