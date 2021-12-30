<?php

namespace Livramatheus\PlanetgameBack;
use Livramatheus\PlanetgameBack\Core\Router;

require __DIR__ . '/vendor/autoload.php';

$Router = new Router();
$Router->addPage('Genre')->addPage('Publisher')->addPage('Game');
$Router->initRouter();
$Router->requirePage();