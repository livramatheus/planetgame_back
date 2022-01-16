<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;

use Exception;

class DatabaseException extends Exception {
    
    protected $message = "Something went wrong with the database";

}