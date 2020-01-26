<?php

namespace Pool\Core\Model\Factory;

use Pool\Core\Model\NoteTable;
use Pool\TableGateway\TableGateway;
use Psr\Container\ContainerInterface;

class NoteTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $pdo = $container->get(\PDO::class);
        $tableGateway = new TableGateway($pdo, 'notes');
        return new NoteTable($tableGateway);
    }
}