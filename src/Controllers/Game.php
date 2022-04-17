<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;
use Livramatheus\PlanetgameBack\Core\Exceptions\BadLanguageException;
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

/**
 * Game controller class
 * 
 * @package Controller
 * @author Matheus do Livramento
 */
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

    /**
     * Sends to front-end a list of every game present on database
     * This function has two possible outcomes:
     * 1. If the requester is an Admin, it will include unnaproved games in the result set
     * 2. Otherwise, unnaproved games will be ommited
     */
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

    /**
     * Sends to front-end the game requested through queryparams
     * This function has three possible outcomes:
     * 1. If the requester is an Admin, it may return unnaproved games
     * 2. If the requester is not an Admin and he tries to fetch a unnaproved game
     * a message stating that the game was not found will be sent to the client
     * 3. Sends an error message in case the does not exist
     */
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

    /**
     * Deletes the game requested through queryparams and sends a success message
     * Sends an error message in case the game was not found
     * This action can only be performed by an admin
     * 
     * @throws PermissionException
     */
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
    
    /**
     * Inserts a new game into the database
     * Sends an error message in case of missing fields or bad words
     */
    private function insert() {
        $Response = new Response();

        if ($this->validateInput()) {
            try {
                $this->isProfanityClear();
                $this->ModelGame->insert();
                $Response->setResponseCode(200);
                $Response->setData('Game inserted successfully!');
            } catch (DatabaseException | BadLanguageException $Exception) {
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

    /**
     * Approves an existing game.
     * This action is restricted to Admins by checking the validity of JWT token.
     */
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

    /**
     * Checks bad words in user input when inserting a new Game
     * 
     * @throws BadLanguageException
     */
    private function isProfanityClear() {
        $ProfCheck = new Check();

        $profClear = (
            $ProfCheck->hasProfanity($this->ModelGame->getName())        ||
            $ProfCheck->hasProfanity($this->ModelGame->getReleaseDate()) ||
            $ProfCheck->hasProfanity($this->ModelGame->getAbstract())    ||
            $ProfCheck->hasProfanity($this->ModelGame->getContributor())
        );

        if ($profClear) {
            throw new BadLanguageException();
        }
    }

    /**
     * Checks if mandatory fields for a new game are filled
     * 
     * @return bool
     */
    private function isFilled() : bool {
        return (
            !empty($this->ModelGame->getName())                    &&
            !empty($this->ModelGame->getReleaseDate())             &&
            !empty($this->ModelGame->getModelPublisher()->getId()) &&
            !empty($this->ModelGame->getModelGenre()->getId())
        );
    }

    /**
     * Sends a default response in case of a problematic request
     */
    public function defaultResponse() {
        $Response = new Response();
        $Response->setData(Message::MISSING_PARAMS_ERROR);
        $Response->setResponseCode(400);
        $Response->send();
    }

    /**
     * Generic input validator implementation
     * 
     * @return bool
     */
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

        return $this->isFilled();
    }

    /**
     * Input validator for game approval request
     * 
     * @return bool
     */
    private function validateInputApprove() : bool {
        $this->ModelGame = new ModelGame();

        $data = PostUtils::getPostInput();
        $this->ModelGame->setId(filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT));
        return !empty($this->ModelGame->getId());
    }

    /**
     * Input validator for game removal request
     * 
     * @return bool
     */
    private function validateInputDelete() : bool {
        $this->ModelGame = new ModelGame();

        $data = PostUtils::getPostInput();
        $this->ModelGame->setId(filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT));
        return !empty($this->ModelGame->getId());
    }

}