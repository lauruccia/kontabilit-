<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Questo file va copiato in /public_html/index.php.
// Presuppone che il repository Laravel sia in /home/CPANEL_USER/kontabilit.

if (file_exists($maintenance = __DIR__.'/../kontabilit/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../kontabilit/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../kontabilit/bootstrap/app.php';

$app->handleRequest(Request::capture());
