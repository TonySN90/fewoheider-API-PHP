<?php

namespace app\models;

use app\traits\PrepareConditions;
use app\traits\PrepareFields;
use app\traits\PrepareOrder;
use app\traits\PreparePagination;
use PDO;

abstract class BaseModel
{
    use PrepareConditions, PrepareFields, PrepareOrder, PreparePagination;

    protected PDO $conn;
    protected $table;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function getTableColumns(): array
    {
        $query = "DESCRIBE " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $columns ?: [];
    }

    public function getAll()
    {
        $fields = $this->prepareFields();
        [$conditions, $bindParams] = $this->prepareConditions();
        $order = $this->prepareOrder();
        $pagination = $this->preparePagination();

        $query = "SELECT $fields FROM " . $this->table . $conditions . $order . $pagination;

        $stmt = $this->conn->prepare($query);
        foreach ($bindParams as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getById($id)
    {
        $fields = $this->prepareFields();
        [$conditions, $bindParams] = $this->prepareConditions();

        $query = "SELECT $fields FROM " . $this->table . " WHERE id = :id" . $conditions;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        foreach ($bindParams as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $fields = implode(',', array_keys($data));

        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO " . $this->table . " ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $query = "UPDATE " . $this->table . " SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
