<?php
/**
 * Routes configuration
 */

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {

    // Api routes
    $routes->prefix('api', function(RouteBuilder $routes) {
        $routes->setExtensions(['json']);
        $routes->scope('/users', ['controller' => 'Users'], function(RouteBuilder $routes) {
            $routes->connect('/login', ['action' => 'login', '_method' => 'POST']);
            $routes->connect('/logout', ['action' => 'logout', '_method' => 'POST']);
            $routes->connect('/register', ['action' => 'register', '_method' => 'POST']);
            $routes->connect('/lost-password', ['action' => 'lostPassword', '_method' => 'POST']);
            $routes->connect('/reset-password', ['action' => 'resetPassword', '_method' => 'POST']);
        });
    });

    // App routes
    $routes->scope('/', ['controller' => 'Home'], function (RouteBuilder $routes) {
        $routes->connect('/', ['action' => 'index']);
        $routes->connect('/*', ['action' => 'index']);
    });
});
