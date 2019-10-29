<?php

namespace Pool\Controller;

/**
 * Based on https://github.com/awmpietro/php-front-controller
 */
class FrontController
{
    const DEF_CONTROLLER = 'Notes';
    const DEF_ACTION = 'display';
    const BASE_PATH = '';

    /** @var string */
    private $controller = self::DEF_CONTROLLER;
    /** @var string */
    private $action = self::DEF_ACTION;
    /** @var array */
    private $params;

    public function __construct(array $options = [])
    {
        if (empty($options)) {
            $this->parseUri();
        } else {
            if (isset($options['controller'])) {
                $this->setController($options['controller']);
            }

            if (isset($options['action'])) {
                $this->setAction($options['action']);
            }

            if (isset($options['params'])) {
                $this->setParams($options['params']);
            }
        }
    }

    /**
     * Break a URI string down and derive controller, action and params from it
     */
    private function parseUri(): void
    {
        $uri = trim(parse_url($_REQUEST['uri'], PHP_URL_PATH), '/');
        if ($uri === self::BASE_PATH) {
            $this->setController(self::DEF_CONTROLLER);
            $this->setAction(self::DEF_ACTION);
        } else {
            // todo
        }
    }

    /**
     * Set controller
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $controller = 'Controller\\' . ucfirst(strtolower($controller)) . 'Controller';
        if (!class_exists($controller)) {
            header("HTTP/2.2 404 Not Found");
            echo '<h1>404 Not Found</h1>';
            echo 'The requested page was not found.';
            exit();
        }

        $this->controller = $controller;
    }

    /**
     * Set action
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $reflection = new \ReflectionClass($this->controller);
        if (!$reflection->hasMethod($action)) {
            header("<h1>404 Not Found</h1>");
            echo 'The request page could not be found.';
            exit();
        }

        $this->action = $action;
    }

    /**
     * Set parameters
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Create an instance of the controller and run action
     */
    public function run()
    {
        call_user_func_array(
            [
                new $this->controller,
                $this->action
            ],
            $this->params ?? []
        );
    }
}