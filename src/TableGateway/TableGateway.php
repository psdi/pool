<?php

namespace Pool\TableGateway;

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

    /**
     * {@inheritDoc}
     */
    public function insert(array $data)
    {
        $query = "INSERT INTO $this->table";
        list('cols' => $columns, 'vals' => $values) = $this->buildInsertions($data);
        $query .= " $columns VALUES $values";

        $stmt = $this->pdo->prepare($query);
        foreach ($data as $k => $v) {
            $stmt->bindParam(":$k", $v);
        }
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $data, array $where)
    {
        $query = "UPDATE $this->table SET" 
            . $this->buildAssignments($data)
            . $this->buildCondition($where);

        $stmt = $this->pdo->prepare($query);
        foreach (array_merge($data, $where) as $key => $value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(array $where = [])
    {
        // Prevent deletion of all items
        if (count($where) === 0) {
            exit;
        }

        $query = "DELETE FROM $this->table" . $this->buildCondition($where);
        $stmt = $this->pdo->prepare($query);
        foreach ($where as $key => $value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
    }

    private function buildCondition(array $params): string
    {
        foreach (array_keys($params) as $column) {
            $placeholderPairs[] = "`$column` = :$column";
        }

        return ' WHERE ' . implode(' AND ', $placeholderPairs);
    }

    private function buildInsertions(array $params)
    {
        $cols = [];
        $vals = [];
        foreach (array_keys($params) as $column) {
            $vals[] = ':' . $column;
            $cols[] = "$this->table.$column";
        }

        return [
            'cols' => '(' . implode(', ', $cols) . ')',
            'vals' => '(' . implode(', ', $vals) . ')',
        ];
    }

    private function buildAssignments(array $params)
    {
        $assigns = [];
        foreach (array_keys($params) as $column) {
            $assigns[] = $this->table . '.' . $column . ' = ' . ':' . $column;
        }
        return implode(', ', $assigns);
    }
}