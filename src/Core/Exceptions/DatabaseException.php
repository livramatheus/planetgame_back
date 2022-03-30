<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;

class DatabaseException extends Exception {
    
    protected $message = Message::DB_ERROR;

}