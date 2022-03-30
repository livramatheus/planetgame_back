<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;
use Exception;

class EnvironmentVarsException extends Exception {

    protected $message = "Error while getting environment variables";

}