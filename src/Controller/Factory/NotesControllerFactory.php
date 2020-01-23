<?php

namespace Pool\Controller\Factory;

use Pool\Controller\NotesController;
use Pool\Http\Request;
use Pool\Service\NoteService;
use Psr\Container\ContainerInterface;
use Twig\Environment;

class NotesControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $renderer = $container->get(Environment::class);
        $request = $container->get(Request::class);
        $noteService = $container->get(NoteService::class);
        return new NotesController($renderer, $request, $noteService);
    }
}