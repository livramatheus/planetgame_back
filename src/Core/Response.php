<?php

namespace Livramatheus\PlanetgameBack\Core;

/**
 * Manages every response sent back to the front-end
 * 
 * @package Core
 * @author Matheus do Livramento
 */
class Response {

    private $data;
    private $responseCode;

    public function __construct() {
        $this->setResponseCode(200);
    }

    public function getData() {
        return $this->data;
    }

    public function getResponseCode() : int {
        return $this->responseCode;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setResponseCode(int $responseCode) {
        $this->responseCode = $responseCode;
    }

    /**
     * Prepares a JSON response
     */
    public function getDataJson() : string {
        return json_encode(['data' => $this->data]);
    }

    /**
     * Sends a response
     */
    public function send() {
        header('Content-Type: application/json');
        http_response_code($this->getResponseCode());
        echo $this->getDataJson();
    }
}
