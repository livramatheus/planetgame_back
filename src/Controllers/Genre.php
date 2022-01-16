<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Livramatheus\PlanetgameBack\Core\Response,
    Livramatheus\PlanetgameBack\Models\Genre as ModelGenre,
    Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;
use Livramatheus\PlanetgameBack\Interfaces\ApiController;

class Genre implements DefaultApiResponse, ApiController {

    /** @var ModelGenre */
    private ModelGenre $ModelGenre;

    private $action;
    private $getParams;

    public function init($action, $getParams) : void {
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
        $this->ModelGenre = new ModelGenre();
        $Response   = new Response();

        $Response->setData($this->ModelGenre->getAll());
        $Response->setResponseCode(200);
        $Response->send();
    }

}