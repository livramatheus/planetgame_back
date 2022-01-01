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

    /**
     * @todo utf8_encode was used to fix strings with special characters,
     * however when "data" is an object or an array, utf8_encode crashes
     * for now, it will be removed
     */
    public function getDataJson() : string {
        return json_encode(['data' => $this->data]);
    }

    public function send() {
        header('Content-Type: application/json');
        http_response_code($this->getResponseCode());
        echo $this->getDataJson();
    }
}
