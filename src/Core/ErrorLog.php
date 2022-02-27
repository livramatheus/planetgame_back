<?php

namespace Livramatheus\PlanetgameBack\Core;

use Exception;

class ErrorLog {

    public static function log(Exception $Exception) {
        $msg = 'Error: ';
        $msg .= $Exception->getMessage() . ' | ';
        $msg .= $Exception->getFile() . ' at line ';
        $msg .= $Exception->getLine();

        error_log($msg);
    }

}