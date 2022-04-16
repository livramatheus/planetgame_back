<?php

namespace Livramatheus\PlanetgameBack\Interfaces;

/**
 * Interface to standardize input validation for APIs
 * 
 * @package Interfaces
 * @author Matheus do Livramento
 */
interface InputValidation {

    public function validateInput() : bool;

}