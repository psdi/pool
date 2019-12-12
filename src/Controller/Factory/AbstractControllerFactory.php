<?php

namespace Pool\Controller\Factory;

use Pool\Controller\AbstractController;
use Pool\Http\Request;
use Psr\Container\ContainerInterface;
use Twig\Environment;

class AbstractControllerFactory
{
    protected $allowedControllers = [];

    /**
     * The AbstractControllerFactory constructor
     * 
     * @param array $controllers
     */
    public function __construct(array $controllers = [])
    {
        $this->allowedControllers = $controllers;
    }

    /**
     * Create an instance of a child class of `AbstractController`
     * 
     * @param string|mixed $controllerType
     * @param ContainerInterface $container
     * @throws \RuntimeException
     * 
     * @return AbstractController
     */
    public function create($controllerType, ContainerInterface $container)
    {
        // TODO: potentially remove this if-block if you always check beforehand anyway
        if (!$this->canCreate($controllerType, $container)) {
            throw new \RuntimeException('Unrecognized controller ' . $controllerType);
        }

        /** @var AbstractController $controller */
        $controller = new $controllerType(
            $container->get(Environment::class),
            $container->get(Request::class)
        );

        return $controller;
    }

    /**
     * A controller can be created if:
     *  - it is listed in `$this->allowedControllers`,
     *  - it is an instance of `AbstractController`, and
     *  - its dependencies are present
     * 
     * @param string|mixed $controllerType
     * @param ContainerInterface $container
     * @return bool
     */
    public function canCreate($controllerType, ContainerInterface $container): bool
    {
        return in_array($controllerType, $this->allowedControllers)
            && is_subclass_of($controllerType, AbstractController::class)
            && $container->has(Environment::class)
            && $container->has(Request::class);
    }
}
