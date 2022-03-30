<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;
use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;

class PermissionException extends Exception {

    protected $message = Message::CREDENTIALS_ERROR;

}