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

    $pdo = initializePDO($config['db'] ?? []);
    $container->set(PDO::class, $pdo);

    return $container;
};

function initializePDO(array $dbData) {
    $allKeys = array_diff(
        array_keys($dbData),
        ['driver', 'host', 'dbname', 'user', 'pass']
    );
    
    if (count($allKeys) !== 0) {
        throw new RuntimeException('All database details (driver, host, dbname, user, pass) have to be supplied in `config.php`.');
    }

    list('driver' => $driver, 'host' => $host, 'dbname' => $dbname, 'user' => $user, 'pass' => $pass) = $dbData;

    try {
        $pdo = new PDO("$driver:host=$host;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }

    return $pdo;
}
