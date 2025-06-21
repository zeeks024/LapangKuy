<?php
/**
 * Laravel Entry Point for cPanel Hosting
 * File ini harus diletakkan di /public_html/ (document root domain)
 * 
 * Path struktur yang diharapkan:
 * /home/laps3233/lapangkuy_laravel/    <- Laravel project
 * /home/laps3233/public_html/          <- Document root (file ini)
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../lapangkuy_laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../lapangkuy_laravel/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../lapangkuy_laravel/bootstrap/app.php')
    ->handleRequest(Request::capture());
