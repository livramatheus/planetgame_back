<?php

namespace Livramatheus\PlanetgameBack\Core;

class Router {

    const PAGE_PARAM_NAME   = 'page';
    const ACTION_PARAM_NAME = 'action';
    const PARAMS_PARAM_NAME = 'params';

    private $page;
    private $action;
    private $params;
    private array $pages = [];

    public function initRouter() {
        $this->page   = filter_input(INPUT_GET, self::PAGE_PARAM_NAME  , FILTER_SANITIZE_STRING);
        $this->action = filter_input(INPUT_GET, self::ACTION_PARAM_NAME, FILTER_SANITIZE_STRING);
        $this->params = filter_input(INPUT_GET, self::PARAMS_PARAM_NAME, FILTER_SANITIZE_STRING);
    }

    public function requirePage() {
        if (in_array($this->page, $this->pages)) {
            $class = '\\Livramatheus\\PlanetgameBack\\Controllers\\' . ucfirst($this->page);
            $Page = new $class;
            $Page->init($this->action, $this->params);
        } else {
            $Response = new Response();
            $Response->setData('Page not found.');
            $Response->setResponseCode(404);
            $Response->send();
        }
    }

    public function addPage(string $pageName) {
        $this->pages[] = strtolower($pageName);
        return $this;
    }

}