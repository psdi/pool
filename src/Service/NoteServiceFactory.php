<?php

namespace Pool\Service;

use Pool\Core\Model\NoteTable;
use Pool\Core\Model\TextTable;
use Psr\Container\ContainerInterface;

class NoteServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $noteTable = $container->get(NoteTable::class);
        $textTable = $container->get(TextTable::class);
        return new NoteService($noteTable, $textTable);
    }
}