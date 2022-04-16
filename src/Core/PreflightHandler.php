<?php

namespace Livramatheus\PlanetgameBack\Core;

/**
 * In charge of managing Preflight requests sent by the front-end
 * 
 * @package Core
 * @author Matheus do Livramento
 */
class PreflightHandler {

    public function __construct() {
        $method  = $_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]  ?? null;
        $headers = $_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"] ?? null;

        $method  = filter_var($method , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $headers = filter_var($headers, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (
            (!empty($method) && in_array($method, ['POST', 'GET'])) &&
            (!empty($headers) && $headers == 'authorization')
        ) {
            die();
        }
    }
}
