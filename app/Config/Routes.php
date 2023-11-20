<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->resource('books', ['only' => ['index', 'show']]);
$routes->get('/search/(:segment)', 'Books::search/$1');
$routes->get('/statistics', 'Books::statistics');
$routes->get('/download', 'Books::download');
