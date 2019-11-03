<?php

use Pool\Container\Container;
use Pool\Controller\Factory\AbstractControllerFactory;
use Pool\Http\Request;
use Twig\Environment;

return function ($config) {
    $container = new Container();
    $twig = (require 'twig.init.php')($config);
    $factories = isset($config['factories']) ? $config['factories'] : [];

    $container->set(Request::class, Request::initialize());
    $container->set(Environment::class, $twig);
    $container->set('config', $config);

    foreach ($factories as $factoryName => $controllers) {
        if ($container->has($factoryName) || !class_exists($factoryName)) {
            continue;
        }

        /** @var AbstractControllerFactory $cFactory */
        $cFactory = new $factoryName($controllers);
        $container->set($factoryName, $cFactory);
    }

    return $container;
};
