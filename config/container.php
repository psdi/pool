<?php

use Pool\TableGateway\TableGateway;
use Pool\Container\Container;
use Pool\Core\Model\NoteTable;
use Pool\Core\Model\TextTable;
use Pool\Http\Request;
use Twig\Environment;

return function ($config) {
    $container = new Container();
    $twig = (require 'twig.init.php')($config);
    $factories = isset($config['factories']) ? $config['factories'] : [];

    $container->set(Request::class, Request::initialize());
    $container->set(Environment::class, $twig);
    $container->set('config', $config);

    $pdo = initializePDO($config['db'] ?? []);
    $container->set(PDO::class, $pdo);
    $notesGateway = new TableGateway($pdo, 'notes');
    $textsGateway = new TableGateway($pdo, 'notes_texts');
    $container->set(NoteTable::class, new NoteTable($notesGateway));
    $container->set(TextTable::class, new TextTable($textsGateway));

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

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO("$driver:host=$host;dbname=$dbname", $user, $pass, $options);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }

    return $pdo;
}
