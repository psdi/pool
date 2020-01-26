<?php

namespace Pool;

use PDO;
use Psr\Container\ContainerInterface;

class PdoFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbData = $container->get('config')['db'] ?? [];

        $allKeys = array_diff(
            ['driver', 'host', 'dbname', 'user', 'pass'],
            array_keys($dbData)
        );
        
        if (count($allKeys) !== 0) {
            throw new \RuntimeException('All database details (driver, host, dbname, user, pass) have to be supplied in `config.php`.');
        }
    
        list('driver' => $driver, 'host' => $host, 'dbname' => $dbname, 'user' => $user, 'pass' => $pass) = $dbData;
    
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    
        try {
            $pdo = new PDO("$driver:host=$host;dbname=$dbname", $user, $pass, $options);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    
        return $pdo;
    }
}