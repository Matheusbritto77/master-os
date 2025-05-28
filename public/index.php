<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    // Redirecionar para HTTPS
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect_url, true, 301);
    exit();
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
