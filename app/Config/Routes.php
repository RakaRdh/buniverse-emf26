<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = $routes ?? Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->match(['get', 'head'], '/', 'Home::index');
$routes->match(['get', 'head'], 'preview-hero', 'Home::previewHero');
$routes->match(['get', 'head'], 'preview-hero2', 'Home::previewHero2');
$routes->match(['get', 'head'], 'preview-about', 'Home::previewAbout');
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

// Public Visitor Registration Routes (Non-SPA)
$routes->get('register', 'RegistrationController::index');
$routes->post('register/submit', 'RegistrationController::submit');
$routes->get('register/success/(:segment)', 'RegistrationController::success/$1');

// Disguised Receptionist Desk Portal (with 6-Digit Passcode Session)
$routes->get('gate/portal_desk_ops_access_verify', 'RegistrationController::gatePortal');
$routes->post('gate/portal_desk_ops_access_verify/verify', 'RegistrationController::verifyGatePasscode');
$routes->get('gate/portal_desk_ops_access_verify/lock', 'RegistrationController::lockGatePortal');
$routes->post('gate/portal_desk_ops_access_verify/checkin', 'RegistrationController::proxyCheckin');
$routes->get('gate/portal_desk_ops_access_verify/search', 'RegistrationController::proxySearch');
$routes->get('gate/portal_desk_ops_access_verify/stats', 'RegistrationController::proxyStats');

// On-The-Spot (OTS) Registration Module (Model A Murni - Instant Check-In)
$routes->get('gate/portal_desk_ops_access_verify/registration', 'RegistrationController::gateRegistration');
$routes->post('gate/portal_desk_ops_access_verify/registration/submit', 'RegistrationController::submitGateRegistration');
