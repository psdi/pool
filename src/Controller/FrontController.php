<?php

namespace Pool\Controller;

use Pool\Controller\Factory\AbstractControllerFactory;
use Pool\Http\Request;
use Psr\Container\ContainerInterface;

/**
 * Based on https://github.com/awmpietro/php-front-controller
 */
class FrontController
{
    const DEF_CONTROLLER = self::CONTROLLER_NAMEPACE . 'NotesController';
    const DEF_ACTION = 'index';
    const BASE_PATH = '';
    const CONTROLLER_NAMEPACE = 'Pool\\Controller\\';

    /** @var string */
    private $controller = self::DEF_CONTROLLER;
    /** @var string */
    private $action = self::DEF_ACTION;
    /** @var array */
    private $params = [];
    /** @var ContainerInterface */
    private $container;
    /** @var Request|null */
    private $request;

    public function __construct(ContainerInterface $container, array $options = [])
    {
        $this->container = $container;
        $this->request = $container->has(Request::class) ? $container->get(Request::class) : null;

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
        // TODO: throw an error instead of falling back on an empty string
        $uri = $this->request !== null ? $this->request->getRequestUri() : '';

        if ($uri === self::BASE_PATH) {
            $this->setController(self::DEF_CONTROLLER);
            $this->setAction(self::DEF_ACTION);
            return;
        }

        list($controller, $action, $params) = array_pad(explode('/', $uri, 3), 3, '');
        if (!empty($controller)) {
            $this->setController($controller);
        }

        if (!empty($action)) {
            $this->setAction($action);
        }

        if (!empty($params)) {
            $this->setParams(explode('/', $params));
        }
    }

    /**
     * Set controller
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $controller = self::CONTROLLER_NAMEPACE . ucfirst(strtolower($controller)) . 'Controller';
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
            header("HTTP/2.2 400 Bad Request");
            echo '<h1>400 Bad Request</h1>';
            echo 'The requested page was not found.';
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
     * Look for controller's factory in the config file, instantiates this,
     * and through it create the controller and call the specified action
     *
     * @throws \RuntimeException
     */
    public function run()
    {
        $factories = $this->container->get('config')['factories'] ?? [];
        if (empty($factories)) {
            throw new \RuntimeException("No specified 'factories' list found in config.php.");
        }

        // Get factory for $this->controller as stated in config/config.php
        $fInstance = false;
        foreach (array_keys($factories) as $factoryName) {
            if (!$this->container->has($factoryName)) {
                continue;
            }

            /** @var AbstractControllerFactory $fInstance */
            $fInstance = $this->container->get($factoryName);

            if ($fInstance->canCreate($this->controller, $this->container)) {
                break;
            }

            $fInstance = false;
        }

        if (!$fInstance) {
            throw new \RuntimeException('Could not find a factory for ' . $this->controller);
        }

        call_user_func_array(
            [
                $fInstance->create($this->controller, $this->container),
                $this->action
            ],
            $this->params
        );
    }
}