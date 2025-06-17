<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->resource('member', ['controller' => 'MemberController']);
    $routes->resource('produk', ['controller' => 'ProdukController']);
});

$routes->resource('api/auth/login', ['controller' => 'AuthController::login']);
$routes->resource('api/auth/register', ['controller' => 'AuthController::register']);
$routes->options('(:any)', 'CorsController::options');
