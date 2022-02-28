<?php

namespace Livramatheus\PlanetgameBack\Core;

const POST_DATA_INPUT = 'data';

class PostUtils {
    
    /**
     * Since the web server can be reached via a request software
     * and the front-end app itself, the following process grants
     * that the requests will work on both situations
     */
    public static function getPostInput() {
        $data        = filter_input(INPUT_POST, POST_DATA_INPUT);
        $dataDecoded = json_decode($data, true);

        if (!empty($dataDecoded)) {
            return $dataDecoded;
        }

        return $_POST;
    }

}