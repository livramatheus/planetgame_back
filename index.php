<?php

namespace Livramatheus\PlanetgameBack;

use Livramatheus\PlanetgameBack\Core\PreflightHandler;
use Livramatheus\PlanetgameBack\Core\Router;

require __DIR__ . '/vendor/autoload.php';

/* 
 * This file will only be required on dev environment,
 * while on production, env vars will be set by the host
 * 
*/
if (file_exists(__DIR__ . './src/Config/env.local.php')) {
    require __DIR__ . './src/Config/env.local.php';
}

header("Access-Control-Allow-Origin: " . getenv('CLIENT_URL'));
header("Access-Control-Allow-Headers: authorization");
new PreflightHandler();

$Router = new Router();
$Router->addPage('Genre')->addPage('Publisher')->addPage('Game')->addPage('Admin');
$Router->initRouter();
$Router->requirePage();