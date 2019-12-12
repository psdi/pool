<?php

require dirname(__FILE__, 2) . '/src/Autoloader.php';

$basepath = isset($config['basepath']) ? $config['basepath'] : '';

$autoloader = new Pool\Autoloader($basepath);

$autoloader->addNamespaceGroup('Pool', 'src/', function (Pool\Autoloader $a) {
    $a->addNamespace('Container', 'Container/');
    $a->addNamespace('Controller', 'Controller/');
    $a->addNamespaceGroup('Core', 'Core/', function (Pool\Autoloader $a) {
        $a->addNamespace('Factory', 'Factory/');
        $a->addNamespace('Object', 'Object/');
    });
    $a->addNamespace('Http', 'Http/');
});

$autoloader->register();
