<?php

namespace Pool\Controller;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'factories' => $this->getFactories(),
        ];
    }

    public function getFactories(): array
    {
        return [
            \Pool\Controller\NotesController::class => \Pool\Controller\Factory\NotesControllerFactory::class,
        ];
    }

}