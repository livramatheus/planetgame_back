<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
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
        $Response->setData(Message::MISSING_PARAMS_ERROR);
        $Response->setResponseCode(400);
        $Response->send();
    }

    private function getAll() {
        $this->ModelGenre = new ModelGenre();
        $Response = new Response();

        try {
            $data = $this->ModelGenre->getAll();
            $Response->setData($data);
            $Response->setResponseCode(200);
        } catch (DatabaseException $Exception) {
            $Response->setData($Exception->getMessage());
            $Response->setResponseCode(400);
        } catch (Exception $Exception) {
            $Response->setData(Message::UNKNOWN_ERROR);
            $Response->setResponseCode(400);
        }
        
        $Response->send();
    }

}