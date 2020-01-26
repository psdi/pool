<?php

namespace Pool\Core\Model\Factory;

use Pool\Core\Model\TextTable;
use Pool\TableGateway\TableGateway;
use Psr\Container\ContainerInterface;

class TextTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $pdo = $container->get(\PDO::class);
        $tableGateway = new TableGateway($pdo, 'notes_texts');
        return new TextTable($tableGateway);
    }
}