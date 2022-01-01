<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Livramatheus\PlanetgameBack\Core\Response,
    Livramatheus\PlanetgameBack\Models\Genre as ModelGenre,
    Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;

class Genre implements DefaultApiResponse {

    private $action;
    private $getParams;

    public function init($action, $getParams) {
        $this->action    = $action;
        $this->getParams = $getParams;
        
        $this->call();
    }

    private function call() {
        switch ($this->action) {
            case 'getall':
                $this->getAll();
                break;
            default:
                $this->defaultResponse();
                break;
        }
    }

    public function defaultResponse() {
        $Response = new Response();
        $Response->setData('Missing parameters or actions.');
        $Response->setResponseCode(400);
        $Response->send();
    }

    private function getAll() {
        $ModelGenre = new ModelGenre();
        $Response   = new Response();

        $Response->setData($ModelGenre->getAll());
        $Response->setResponseCode(200);
        $Response->send();
    }

}