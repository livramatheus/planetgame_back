<?php

namespace Livramatheus\PlanetgameBack\Core;

/**
 * Manages APP routing system
 * 
 * @package Core
 * @author Matheus do Livramento
 */
class Router {

    // The desired page by the client is represented by 'page' keyword
    const PAGE_PARAM_NAME   = 'page';

    // The desired action to be performed is represented by 'action' keyword
    const ACTION_PARAM_NAME = 'action';

    // If the request use parameters, it should be represented by 'params' keyword
    const PARAMS_PARAM_NAME = 'params';

    private $page;
    private $action;
    private $params;
    private array $pages = [];

    /**
     * Initializes the router by collecting 'page', 'action' and 'params' info
     */
    public function initRouter() {
        $this->page   = filter_input(INPUT_GET, self::PAGE_PARAM_NAME  , FILTER_SANITIZE_STRING);
        $this->action = filter_input(INPUT_GET, self::ACTION_PARAM_NAME, FILTER_SANITIZE_STRING);
        $this->params = filter_input(INPUT_GET, self::PARAMS_PARAM_NAME, FILTER_SANITIZE_STRING);
    }

    /**
     * Checks if requested page is valid. If it is valid, it will automatically require it's controller.
     * If is not valid it will send a 404 error back to the client.
     */
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

    /**
     * Adds a page endpoint. Every valid page should be present in this array
     */
    public function addPage(string $pageName) {
        $this->pages[] = strtolower($pageName);
        return $this;
    }

}