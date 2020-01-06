<?php

namespace Core\TableGateway;

use PDO;

class TableGateway implements TableGatewayInterface
{
    /** @var PDO|null */
    private $pdo;
    /** @var string */
    private $table;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * {@inheritDoc}
     */
    public function select(array $where = [], string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = "SELECT * FROM $this->table";
        if (count($where) > 0) {
            $query .= $this->buildCondition($where);
        }

        if (strlen($orderBy)) {
            $query .= " ORDER BY :orderBy :direction";
        }

        $stmt = $this->pdo->prepare($query);

        if (count($where) > 0) {
            foreach ($where as $column => $value) {
                $stmt->bindParam(":$column", $value);
            }
        }

        if (strlen($orderBy)) {
            $stmt->bindParam(':orderBy', $orderBy);
            $stmt->bindParam(':direction', strtoupper($direction));
        }
        $stmt->execute();

        $rows = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    private function buildCondition(array $params): string
    {
        foreach (array_keys($params) as $column) {
            $placeholderPairs[] = "`$column` = :$column";
        }

        return ' WHERE ' . implode(' AND ', $placeholderPairs);
    }
}