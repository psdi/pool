<?php

namespace Pool;

class ConfigProvider
{
    public function __invoke()
    {
        return $this->getDataSource() +
            $this->getTables() +
            $this->getServices();
    }

    public function getDataSource()
    {
        return [
            \PDO::class => \Pool\PdoFactory::class
        ];
    }

    public function getTables()
    {
        return [
            \Pool\Core\Model\NoteTable::class => \Pool\Core\Model\Factory\NoteTableFactory::class,
            \Pool\Core\Model\TextTable::class => \Pool\Core\Model\Factory\TextTableFactory::class,
        ];
    }

    public function getServices()
    {
        return [
            \Pool\Service\NoteService::class => \Pool\Service\NoteServiceFactory::class,  
        ];
    }
}