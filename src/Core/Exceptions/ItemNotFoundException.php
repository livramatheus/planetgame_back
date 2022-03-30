<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;
use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;

class ItemNotFoundException extends Exception {
    
    protected $message = Message::ITM_NOT_FOUND_NOTICE;

}