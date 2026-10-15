<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->match(['get', 'head'], '/', 'Home::index');
$routes->post('api/track-ticket', 'Home::trackTicket');
$routes->match(['get', 'post'], 'api/clear-cache', 'Home::clearCache');

// Q&A Routes
$routes->get('qna/dash_board_guest_list', 'QnaController::dashboard');
$routes->get('dash_board_guest_list', 'QnaController::dashboard');
$routes->get('qna/dashboard', 'QnaController::dashboard'); // Alias fallback
$routes->get('qna/dashboards', 'QnaController::dashboard');
$routes->get('qna/api/list', 'QnaController::apiList');
$routes->post('qna/submit', 'QnaController::submit');

// Q&A Form Routes
$routes->get('qna/(:num)/(:num)/(:num)', 'QnaController::daySessionItem/$1/$2/$3'); // qna/(hari)/(idsesi)/(iditem)
$routes->get('qna/(:num)/(:num)', 'QnaController::daySession/$1/$2'); // qna/(hari)/(idsesi)
$routes->get('qna/(:num)', 'QnaController::sessionOnly/$1'); // qna/(idsesi)
$routes->get('qna/session/(:num)/item/(:num)', 'QnaController::sessionItem/$1/$2');
$routes->get('qna/day/(:num)/session/(:num)/item/(:num)', 'QnaController::daySessionItem/$1/$2/$3');
$routes->get('qna/day/(:num)/session/(:num)', 'QnaController::daySession/$1/$2');
$routes->get('qna/day=(:num)&session=(:num)', 'QnaController::daySession/$1/$2');
$routes->get('qna/session/(:num)', 'QnaController::sessionOnly/$1');
$routes->get('qna', 'QnaController::index');

// Media Proxy Route under Frontend domain (GET & HEAD)
$routes->match(['get', 'head'], 'uploads/(:any)', 'MediaController::serve/$1');
