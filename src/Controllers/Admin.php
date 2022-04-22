<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\Enums\Message;
use Livramatheus\PlanetgameBack\Core\Response;
use Livramatheus\PlanetgameBack\Core\Exceptions\PermissionException;
use Livramatheus\PlanetgameBack\Core\JwtHandler;
use Livramatheus\PlanetgameBack\Core\PostUtils;
use Livramatheus\PlanetgameBack\Interfaces\ApiController;
use Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;
use Livramatheus\PlanetgameBack\Interfaces\InputValidation;
use Livramatheus\PlanetgameBack\Models\Admin as ModelAdmin;

/**
 * Admin controller class
 * 
 * @package Controller
 * @author Matheus do Livramento
 */
class Admin implements ApiController, DefaultApiResponse, InputValidation {

    /** @var ModelAdmin */
    private ModelAdmin $ModelAdmin;

    private $action;
    private $getParams;

    public function init($action, $getParams) : void {
        $this->action    = $action;
        $this->getParams = $getParams;
        
        $this->call();
    }

    private function call() {
        switch ($this->action) {
            case 'login':
                $this->login();
                break;
            default:
                $this->defaultResponse();
                break;
        }
    }

    /**
     * Manages loggin attempt by user. It may send the following responses:
     * JWT token on success - 200
     * Denial notice in case of wrong credentials - 401
     * Generic error message for assorted errors - 400
     * 
     * @throws PermissionException
     */
    private function login() {
        $Response = new Response();
        
        if ($this->validateInput()) {
            try {
                $res = $this->ModelAdmin->login();

                if (!$res) {
                    throw new PermissionException();
                }
                
                $payload = [
                    'user_name'  => $this->ModelAdmin->getUsername(),
                    'first_name' => $this->ModelAdmin->getFirstName(),
                    'last_name'  => $this->ModelAdmin->getLastName()
                ];

                $Response->setData(JwtHandler::createToken($payload));
                $Response->setResponseCode(200);
            } catch (PermissionException $Error) {
                $Response->setResponseCode(401);
                $Response->setData($Error->getMessage());
            } catch (Exception $Error) {
                $Response->setResponseCode(400);
                $Response->setData(Message::UNKNOWN_ERROR);
            }
        } else {
            $Response->setResponseCode(400);
            $Response->setData(Message::BAD_REQUEST_ERROR);
        }

        $Response->send();
    }

    /**
     * Checks if mandatory fields for the login action are filled
     * 
     * @return bool
     */
    private function isFilled() : bool {
        return (
            !empty($this->ModelAdmin->getUsername()) &&
            !empty($this->ModelAdmin->getPassword())
        );
    }

    /**
     * Generic input validator implementation
     * 
     * @return bool
     */
    public function validateInput() : bool {
        $this->ModelAdmin = new ModelAdmin;

        $data = PostUtils::getPostInput();

        $this->ModelAdmin->setUsername(filter_var($data['user_name']), FILTER_SANITIZE_STRING);
        $this->ModelAdmin->setPassword(filter_var($data['password'] ), FILTER_SANITIZE_STRING);

        return $this->isFilled();
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

}