<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\ItemNotFoundException;
use Livramatheus\PlanetgameBack\Core\Exceptions\PermissionException;
use Livramatheus\PlanetgameBack\Core\JwtHandler;
use Livramatheus\PlanetgameBack\Core\PostUtils;
use Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;
use Livramatheus\PlanetgameBack\Models\Game as ModelGame;
use Livramatheus\PlanetgameBack\Models\Genre as ModelGenre;
use Livramatheus\PlanetgameBack\Models\Publisher as ModelPublisher;
use Livramatheus\PlanetgameBack\Interfaces\InputValidation;
use Livramatheus\PlanetgameBack\Core\Response;
use Livramatheus\PlanetgameBack\Interfaces\ApiController;
use mofodojodino\ProfanityFilter\Check;

class Game implements DefaultApiResponse, InputValidation, ApiController {

    /** @var ModelGame */
    private ModelGame $ModelGame;

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
            case 'get':
                $this->get();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'insert':
                $this->insert();
                break;
            case 'approve':
                $this->approve();
                break;
            default:
                $this->defaultResponse();
                break;
        }
    }

    private function getAll() {
        $this->ModelGame = new ModelGame();
        $Response = new Response();
        $tokenValid = JwtHandler::checkToken();
        
        try {
            $data = $this->ModelGame->getAll($tokenValid);
            $Response->setData($data);
            $Response->setResponseCode(200);
        } catch (DatabaseException $Exception) {
            $Response->setResponseCode(400);
            $Response->setData($Exception->getMessage());            
        } catch (Exception $Exception) {
            $Response->setResponseCode(400);
            $Response->setData(Message::UNKNOWN_ERROR);
        }

        $Response->send();
    }

    private function get() {
        $Response   = new Response();
        $tokenValid = JwtHandler::checkToken();
        
        if (!empty($this->getParams)) {
            $this->ModelGame = new ModelGame();
            $this->ModelGame->setId($this->getParams);
            
            try {
                $dbData = $this->ModelGame->get($tokenValid);
                $Response->setData($dbData);
                $Response->setResponseCode(200);
            } catch (DatabaseException | ItemNotFoundException $Exception) {
                $Response->setData($Exception->getMessage());
                $Response->setResponseCode(400);
            } catch (Exception $Exception) {
                $Response->setData(Message::UNKNOWN_ERROR);
                $Response->setResponseCode(400);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData(Message::MISSING_PARAMS_ERROR);
        }

        $Response->send();
    }

    private function delete() {
        $Response = new Response();

        if ($this->validateInputDelete()) {
            try {
                if (!JwtHandler::checkToken()) {
                    throw new PermissionException();
                }

                $this->ModelGame->delete();
                $Response->setResponseCode(200);
                $Response->setData('Game deleted successfully!');
            } catch (PermissionException $Error) {
                $Response->setResponseCode(401);
                $Response->setData($Error->getMessage());
            } catch (DatabaseException | ItemNotFoundException $Exception) {
                $Response->setResponseCode(400);
                $Response->setData($Exception->getMessage());
            } catch (Exception $Exception) {
                $Response->setResponseCode(400);
                $Response->setData(Message::UNKNOWN_ERROR);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData(Message::MISSING_PARAMS_ERROR);
        }

        $Response->send();
    }
    
    private function insert() {
        $Response = new Response();

        if ($this->validateInput()) {
            try {
                $this->ModelGame->insert();
                $Response->setResponseCode(200);
                $Response->setData('Game inserted successfully!');
            } catch (DatabaseException $Exception) {
                $Response->setResponseCode(400);
                $Response->setData($Exception->getMessage());
            }catch (Exception $Exception) {
                $Response->setResponseCode(400);
                $Response->setData(Message::UNKNOWN_ERROR);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData(Message::MISSING_PARAMS_ERROR);
        }

        $Response->send();
    }

    private function approve() {
        $Response = new Response();

        if ($this->validateInputApprove()) {
            if (JwtHandler::checkToken()) {
                try {
                    $this->ModelGame->approve();

                    $Response->setResponseCode(200);
                    $Response->setData('Game approved successfully!');
                } catch (DatabaseException $Exception) {
                    $Response->setResponseCode(400);
                    $Response->setData($Exception->getMessage());
                } catch (Exception $Exception) {
                    $Response->setResponseCode(400);
                    $Response->setData(Message::UNKNOWN_ERROR);
                }
            } else {
                $Response->setResponseCode(401);
                $Response->setData(Message::CREDENTIALS_ERROR);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData(Message::MISSING_PARAMS_ERROR);
        }

        $Response->send();
    }

    private function isProfanityClear() : bool {
        $ProfCheck = new Check();

        return !(
            $ProfCheck->hasProfanity($this->ModelGame->getName())        ||
            $ProfCheck->hasProfanity($this->ModelGame->getReleaseDate()) ||
            $ProfCheck->hasProfanity($this->ModelGame->getAbstract())    ||
            $ProfCheck->hasProfanity($this->ModelGame->getContributor())
        );
    }

    private function isFilled() : bool {
        return (
            !empty($this->ModelGame->getName())                    &&
            !empty($this->ModelGame->getReleaseDate())             &&
            !empty($this->ModelGame->getModelPublisher()->getId()) &&
            !empty($this->ModelGame->getModelGenre()->getId())
        );
    }

    public function defaultResponse() {
        $Response = new Response();
        $Response->setData(Message::MISSING_PARAMS_ERROR);
        $Response->setResponseCode(400);
        $Response->send();
    }

    public function validateInput() : bool {
        $this->ModelGame = new ModelGame();
        $this->ModelGame->setModelGenre(new ModelGenre());
        $this->ModelGame->setModelPublisher(new ModelPublisher());

        $data = PostUtils::getPostInput();

        $this->ModelGame->setName                   (filter_var($data['name']        , FILTER_SANITIZE_STRING));
        $this->ModelGame->setReleaseDate            (filter_VAR($data['release_date'], FILTER_SANITIZE_STRING));
        $this->ModelGame->setAbstract               (filter_var($data['abstract']    , FILTER_SANITIZE_STRING));
        $this->ModelGame->setContributor            (filter_var($data['contributor'] , FILTER_SANITIZE_STRING));
        $this->ModelGame->getModelPublisher()->setId(filter_var($data['publisher']   , FILTER_SANITIZE_NUMBER_INT));
        $this->ModelGame->getModelGenre()->setId    (filter_var($data['genre']       , FILTER_SANITIZE_NUMBER_INT));

        return ($this->isProfanityClear() && $this->isFilled());
    }

    private function validateInputApprove() : bool {
        $this->ModelGame = new ModelGame();

        $data = PostUtils::getPostInput();
        $this->ModelGame->setId(filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT));
        return !empty($this->ModelGame->getId());
    }

    private function validateInputDelete() : bool {
        $this->ModelGame = new ModelGame();

        $data = PostUtils::getPostInput();
        $this->ModelGame->setId(filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT));
        return !empty($this->ModelGame->getId());
    }

}