<?php

namespace Livramatheus\PlanetgameBack\Core;

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

    public function getDataJson() : string {
        return json_encode(['data' => $this->data]);
    }

    public function send() {
        header('Content-Type: application/json');
        http_response_code($this->getResponseCode());
        echo $this->getDataJson();
    }
}
