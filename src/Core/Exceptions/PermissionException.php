<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;
use Exception;

class PermissionException extends Exception {

    protected $message = "You don't have permission to access this page.";

}