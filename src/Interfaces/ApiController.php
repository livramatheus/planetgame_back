<?php

namespace Livramatheus\PlanetgameBack\Interfaces;

/**
 * Interface for controllers that should work as an API endpoint
 * 
 * @package Interfaces
 * @author Matheus do Livramento
 */
interface ApiController {

    public function init($action, $getParams) : void;

}