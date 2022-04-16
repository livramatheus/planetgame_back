<?php

namespace Livramatheus\PlanetgameBack\Core;

use Exception;

/**
 * Class to standardize and facilitate error logging
 * 
 * @package Core
 * @author Matheus do Livramento
 */
class ErrorLog {

    /**
     * Takes an Exceptions as parameter and logs the error
     * 
     * @param Exception $Exception
     */
    public static function log(Exception $Exception) {
        $msg = 'Error: ';
        $msg .= $Exception->getMessage() . ' | ';
        $msg .= $Exception->getFile() . ' at line ';
        $msg .= $Exception->getLine();

        error_log($msg);
    }

}