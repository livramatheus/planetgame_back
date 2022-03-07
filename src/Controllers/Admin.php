<?php

namespace Livramatheus\PlanetgameBack\Controllers;

use Exception;
use Livramatheus\PlanetgameBack\Core\ErrorLog;
use Livramatheus\PlanetgameBack\Core\Response;
use Livramatheus\PlanetgameBack\Core\Exceptions\PermissionException;
use Livramatheus\PlanetgameBack\Core\JwtHandler;
use Livramatheus\PlanetgameBack\Core\PostUtils;
use Livramatheus\PlanetgameBack\Interfaces\ApiController;
use Livramatheus\PlanetgameBack\Interfaces\DefaultApiResponse;
use Livramatheus\PlanetgameBack\Interfaces\InputValidation;
use Livramatheus\PlanetgameBack\Models\Admin as ModelAdmin;

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
            case 'check':
                $this->check();
                break;
            default:
                $this->defaultResponse();
                break;
        }
    }

    private function check() {
        $Response = new Response();

        if (JwtHandler::checkToken()) {
            $Response->setResponseCode(200);
            $Response->setData('Valid');
        } else {
            $Response->setResponseCode(401);
            $Response->setData('Invalid');
        }

        $Response->send();
    }

    private function login() {
        $Response = new Response();
        
        if ($this->validateInput()) {
            try {
                if (!$this->ModelAdmin->login()) {
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
                // Permission specific exception: no need to log
                $Response->setResponseCode(401);
                $Response->setData($Error->getMessage());
            } catch (Exception $Error) {
                // Structural exceptions: log is needed
                $Response->setResponseCode(400);
                $Response->setData('Something went wrong with your request.');
                ErrorLog::log($Error);
            }
        } else {

        }

        $Response->send();
    }

    private function isFilled() : bool {
        return (
            !empty($this->ModelAdmin->getUsername()) &&
            !empty($this->ModelAdmin->getPassword())
        );
    }

    public function validateInput() : bool {
        $this->ModelAdmin = new ModelAdmin;

        $data = PostUtils::getPostInput();

        $this->ModelAdmin->setUsername(filter_var($data['user_name']), FILTER_SANITIZE_STRING);
        $this->ModelAdmin->setPassword(filter_var($data['password'] ), FILTER_SANITIZE_STRING);

        return $this->isFilled();
    }

    public function defaultResponse() {
        $Response = new Response();
        $Response->setData('Missing parameters or actions.');
        $Response->setResponseCode(400);
        $Response->send();
    }

}