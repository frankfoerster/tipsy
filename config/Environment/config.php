<?php

/**
 * Set the application cache prefix here.
 * This is important because multiple apps on the same server should never share the same cache.
 * Avoids Memcache and APC conflicts.
 */
if (!defined('CACHE_PREFIX')) {
    define('CACHE_PREFIX', 'betz_');
}

/**
 * All available environments are defined here.
 *
 * Structure:
 * ----------
 *
 * [
 *     'live' => [
 *         'domain' => [
 *             'www.example.com',
 *             'example.com',
 *             '...'
 *         ],
 *         'path' => [
 *             'absolute/path1/to/src/',
 *             'absolute/path2/to/src/'
 *         ]
 *     ],
 *     'staging' => [
 *         ...
 *     ],
 *     ...
 * ]
 *
 * Each individual environment must have a custom configuration file,
 * e.g. "app/Config/Environment/environment.live.php".
 *
 * During bootstrap the current environment will be detected automatically.
 *
 * If no environment has been detected then the local configuration
 * from "app/Config/Environment/environment.local.php" will be used.
 */
$availableEnvironments = [
];

/**
 * Configuration settings that will be applied to all environments.
 * These are loaded via Configure::write($configure) and may be overridden in each environment configuration file.
 */
$configure = [
    /**
     * Debug level
     */
    'debug' => false,

    /**
     * Session name
     */
    'Session.cookie' => 'betz',

    /**
     * Cookie name
     */
    'Cookie.name' => 'betzC',

    /**
     * Cache configuration
     */
    'Cache' => [
        'default' => ['prefix' => CACHE_PREFIX],
        '_cake_core_' => ['prefix' => CACHE_PREFIX . 'cake_core_'],
        '_cake_model_' => ['prefix' => CACHE_PREFIX . 'cake_model_'],
        '_cake_routes_' => ['prefix' => CACHE_PREFIX . 'cake_route_']
    ],

    'appTitle' => 'WM 2018'
];
