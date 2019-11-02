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
        \Pool\Controller\NotesController::class => AbstractControllerFactory::class,
    ],
];
