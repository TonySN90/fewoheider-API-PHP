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

    protected $conn;
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
}
