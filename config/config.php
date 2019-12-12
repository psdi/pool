<?php

use Pool\Controller\Factory\AbstractControllerFactory;

return [
    'basepath' => dirname(__FILE__, 2) . '/',
    'templates' => [
        'layout' => '/layout',
        'index' => '/index',
        'shared' => '/shared',
    ],
    'factories' => [
        AbstractControllerFactory::class => [
            \Pool\Controller\NotesController::class,
        ],
    ],
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'pool',
        'user' => 'psdi',
        'pass' => 'H4llucinat10n',
    ]
];
