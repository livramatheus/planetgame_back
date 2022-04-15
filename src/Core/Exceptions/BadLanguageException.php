<?php

namespace Livramatheus\PlanetgameBack\Core\Exceptions;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;

class BadLanguageException extends Exception {

    protected $message = Message::BAD_LANG_ERROR;

}