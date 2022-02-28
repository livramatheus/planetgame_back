<?php

namespace Livramatheus\PlanetgameBack;
use Livramatheus\PlanetgameBack\Core\Router;

require __DIR__ . '/vendor/autoload.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

/* 
 * This file will only be required on dev environment,
 * while on production, env vars will be set by the host
 * 
*/
if (file_exists(__DIR__ . './src/Config/env.local.php')) {
    require __DIR__ . './src/Config/env.local.php';
}

$Router = new Router();
$Router->addPage('Genre')->addPage('Publisher')->addPage('Game');
$Router->initRouter();
$Router->requirePage();