<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;
use Livramatheus\PlanetgameBack\Core\Exceptions\BadLanguageException;
use Livramatheus\PlanetgameBack\Core\Exceptions\DatabaseException;
use Livramatheus\PlanetgameBack\Core\Exceptions\ItemNotFoundException;
use Livramatheus\PlanetgameBack\Core\Response;
use Livramatheus\PlanetgameBack\Interfaces\ApiController;
use Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;
use Livramatheus\PlanetgameBack\Interfaces\InputValidation;
use Livramatheus\PlanetgameBack\Models\Publisher as ModelPublisher;
use mofodojodino\ProfanityFilter\Check;

/**
 * Publisher controller class
 * 
 * @package Controller
 * @author Matheus do Livramento
 */
class Publisher implements DefaultApiResponse, InputValidation, ApiController {

    /** @var ModelPublisher */
    private ModelPublisher $ModelPublisher;

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
            default:
                $this->defaultResponse();
                break;
        }
    }

    /**
     * Sends to front-end a list of every publisher present on database
     */
    private function getAll() {
        $this->ModelPublisher = new ModelPublisher();
        $Response             = new Response();
        
        try {
            $data = $this->ModelPublisher->getAll();
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

    /**
     * Sends to front-end the publisher requested through queryparams
     * Sends an error message in case the publisher was not found
     */
    private function get() {
        $Response = new Response();
        
        if (!empty($this->getParams)) {
            $this->ModelPublisher = new ModelPublisher();
            $this->ModelPublisher->setId($this->getParams);
            
            try {
                $dbData = $this->ModelPublisher->get();
                $Response->setData($dbData);
                $Response->setResponseCode(200);
            } catch (ItemNotFoundException $Exception) {
                $Response->setData($Exception->getMessage());
                $Response->setResponseCode(400);
            } catch (DatabaseException | Exception $Exception) {
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
     * Deletes publisher requested through queryparams and sends a success message
     * Sends an error message in case the publisher was not found
     */
    private function delete() {
        $Response = new Response();
        
        if (!empty($this->getParams)) {
            $this->ModelPublisher = new ModelPublisher();
            $this->ModelPublisher->setId($this->getParams);
            
            try {
                $this->ModelPublisher->delete();
                $Response->setResponseCode(200);
                $Response->setData('Publisher deleted successfully!');
            } catch (ItemNotFoundException $Exception) {
                $Response->setResponseCode(400);
                $Response->setData($Exception->getMessage());
            } catch (DatabaseException | Exception $Exception) {
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
     * Inserts a new publisher into the database
     * Sends an error message in case of missing fields or bad words
     */
    private function insert() {
        $Response = new Response();
        
        if ($this->validateInput()) {            
            try {
                $this->isProfanityClear();
                $this->ModelPublisher->insert();
                $Response->setResponseCode(200);
                $Response->setData('Publisher inserted successfully!');
            } catch (BadLanguageException $Exception) {
                $Response->setResponseCode(400);
                $Response->setData($Exception->getMessage());
            } catch (Exception $Exception) {
                $Response->setResponseCode(400);
                $Response->setData(Message::UNKNOWN_ERROR);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData('Request with missing or wrong parameters.');
        }

        $Response->send();
    }

    /**
     * Checks bad words in user input when inserting a new publisher
     * 
     * @throws BadLanguageException
     */
    private function isProfanityClear() {
        $ProfCheck = new Check();

        $profClear = (
            $ProfCheck->hasProfanity($this->ModelPublisher->getName())    ||
            $ProfCheck->hasProfanity($this->ModelPublisher->getFounded()) ||
            $ProfCheck->hasProfanity($this->ModelPublisher->getLogo())    ||
            $ProfCheck->hasProfanity($this->ModelPublisher->getWebsite())
        );

        if ($profClear) {
            throw new BadLanguageException();
        }
    }

    /**
     * Checks if mandatory fields for a new publisher are filled
     * 
     * @return bool
     */
    private function isFilled() : bool {
        return (
            !empty($this->ModelPublisher->getName())    &&
            !empty($this->ModelPublisher->getFounded()) &&
            !empty($this->ModelPublisher->getLogo())    &&
            !empty($this->ModelPublisher->getWebsite())
        );
    }

    /**
     * Sends a default response in case of a problematic request
     */
    public function defaultResponse() {
        $Response = new Response();
        $Response->setData('Missing parameters or actions.');
        $Response->setResponseCode(400);
        $Response->send();
    }

    /**
     * Generic input validator implementation
     * 
     * @return bool
     */
    public function validateInput() : bool {
        $this->ModelPublisher = new ModelPublisher();

        $this->ModelPublisher->setName   (filter_input(INPUT_POST, 'name'   , FILTER_SANITIZE_STRING));
        $this->ModelPublisher->setFounded(filter_input(INPUT_POST, 'founded', FILTER_SANITIZE_STRING));
        $this->ModelPublisher->setLogo   (filter_input(INPUT_POST, 'logo'   , FILTER_SANITIZE_STRING));
        $this->ModelPublisher->setWebsite(filter_input(INPUT_POST, 'website', FILTER_VALIDATE_URL   ));

        return $this->isFilled();
    }

}