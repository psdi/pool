<?php

use Pool\Container\Container;
use Pool\Http\Request;
use Twig\Environment;

return function ($config) {
    $container = new Container();
    $twig = (require 'twig.init.php')($config);

    $container->set(Request::class, Request::initialize());
    $container->set(Environment::class, $twig);

    return $container;
};
