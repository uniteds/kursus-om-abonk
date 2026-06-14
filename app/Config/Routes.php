<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public Landing Page
$routes->get('/', 'Landing::index');
$routes->get('uploads/(:segment)/(:any)', 'Uploads::show/$1/$2');
$routes->get('course/(:any)', 'Landing\\CourseDetail::index/$1');
$routes->get('artikel', 'Landing\\Articles::index');
$routes->get('artikel/(:any)', 'Landing\\ArticleDetail::index/$1');
$routes->get('sitemap.xml', 'Sitemap::index');

// Guest Routes (redirect if logged in)
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('/login', 'Auth\AuthController::login');
    $routes->post('/login', 'Auth\AuthController::doLogin');
    $routes->get('/register', 'Auth\AuthController::register');
    $routes->post('/register', 'Auth\AuthController::doRegister');
    $routes->get('/forgot-password', 'Auth\AuthController::forgotPassword');
    $routes->post('/forgot-password', 'Auth\AuthController::sendResetLink');
    $routes->post('/reset-password', 'Auth\AuthController::doResetPassword');
});

// Email Verification & Password Reset (public, no auth filter needed)
$routes->get('verify-email/(:any)', 'Auth\AuthController::verifyEmail/$1');
$routes->get('reset-password/(:any)', 'Auth\AuthController::resetPassword/$1');

// Logout
$routes->get('/logout', 'Auth\AuthController::logout');

// Admin Routes
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->get('users/toggle/(:num)', 'Admin\Users::toggle/$1');

    // Categories
    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    // Courses
    $routes->get('courses', 'Admin\Courses::index');
    $routes->get('courses/create', 'Admin\Courses::create');
    $routes->post('courses/store', 'Admin\Courses::store');
    $routes->get('courses/edit/(:num)', 'Admin\Courses::edit/$1');
    $routes->post('courses/update/(:num)', 'Admin\Courses::update/$1');
    $routes->get('courses/delete/(:num)', 'Admin\Courses::delete/$1');

    // Classes
    $routes->get('classes', 'Admin\Classes::index');
    $routes->get('classes/create', 'Admin\Classes::create');
    $routes->post('classes/store', 'Admin\Classes::store');
    $routes->get('classes/view/(:num)', 'Admin\ClassDetail::index/$1');
    $routes->get('classes/edit/(:num)', 'Admin\Classes::edit/$1');
    $routes->post('classes/update/(:num)', 'Admin\Classes::update/$1');
    $routes->get('classes/delete/(:num)', 'Admin\Classes::delete/$1');
    $routes->get('classes/approve-enrollment/(:num)/(:num)', 'Admin\ClassDetail::approveEnrollment/$1/$2');
    $routes->get('classes/reject-enrollment/(:num)/(:num)', 'Admin\ClassDetail::rejectEnrollment/$1/$2');
    $routes->get('classes/delete-content/(:num)/(:num)', 'Admin\ClassDetail::deleteContent/$1/$2');

    // Class Materials
    $routes->get('classes/materials/(:num)', 'Admin\ClassMaterials::index/$1');
    $routes->get('classes/materials/create/(:num)', 'Admin\ClassMaterials::create/$1');
    $routes->post('classes/materials/store/(:num)', 'Admin\ClassMaterials::store/$1');
    $routes->get('classes/materials/edit/(:num)/(:num)', 'Admin\ClassMaterials::edit/$1/$2');
    $routes->post('classes/materials/update/(:num)/(:num)', 'Admin\ClassMaterials::update/$1/$2');
    $routes->get('classes/materials/delete/(:num)/(:num)', 'Admin\ClassMaterials::delete/$1/$2');
    $routes->get('classes/materials/download/(:num)/(:num)', 'Admin\ClassMaterials::download/$1/$2');

    // Content
    $routes->get('content', 'Admin\Content::index');
    $routes->get('content/create', 'Admin\Content::create');
    $routes->post('content/store', 'Admin\Content::store');
    $routes->get('content/edit/(:num)', 'Admin\Content::edit/$1');
    $routes->post('content/update/(:num)', 'Admin\Content::update/$1');
    $routes->get('content/delete/(:num)', 'Admin\Content::delete/$1');

    // Enrollments
    $routes->get('enrollments', 'Admin\Enrollments::index');
    $routes->get('enrollments/approve/(:num)', 'Admin\Enrollments::approve/$1');
    $routes->get('enrollments/reject/(:num)', 'Admin\Enrollments::reject/$1');

    // Announcements
    $routes->get('announcements', 'Admin\Announcements::index');
    $routes->get('announcements/create', 'Admin\Announcements::create');
    $routes->post('announcements/store', 'Admin\Announcements::store');
    $routes->get('announcements/edit/(:num)', 'Admin\Announcements::edit/$1');
    $routes->post('announcements/update/(:num)', 'Admin\Announcements::update/$1');
    $routes->get('announcements/delete/(:num)', 'Admin\Announcements::delete/$1');

    // Settings
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');

    // Profile
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
});

// Member Routes
$routes->group('member', ['filter' => 'member'], function ($routes) {
    $routes->get('/', 'Member\Dashboard::index');
    $routes->get('dashboard', 'Member\Dashboard::index');
    $routes->get('courses', 'Member\Courses::index');
    $routes->get('courses/view/(:num)', 'Member\Courses::view/$1');
    $routes->post('courses/enroll/(:num)', 'Member\Courses::enroll/$1');
    $routes->get('my-courses', 'Member\MyCourses::index');
    $routes->get('class/(:num)', 'Member\ClassDetail::index/$1');
    $routes->get('class-materials/download/(:num)/(:num)', 'Member\ClassMaterialDownload::index/$1/$2');
    $routes->get('profile', 'Member\Profile::index');
    $routes->post('profile/update', 'Member\Profile::update');
});
