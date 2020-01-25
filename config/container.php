<?php

use Pool\ConfigProvider;
use Pool\Container\Container;
use Pool\Http\Request;
use Twig\Environment;

return function ($config) {
    $container = new Container();
    $twig = (require 'twig.init.php')($config);

    $container->set(Request::class, Request::initialize());
    $container->set(Environment::class, $twig);
    $container->set('config', $config);

    $dependencies = new ConfigProvider();
    foreach ($dependencies() as $class => $factory) {
        if (!class_exists($class) || !class_exists($factory)) {
            continue;
        }

        // Create new instance of designated factory
        $instance = new $factory();

        // Pass container as standard parameter
        $container->set($class, $instance($container));
    }

    return $container;
};
