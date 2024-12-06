<?php

namespace app\models;

use app\traits\PrepareConditions;
use app\traits\PrepareFields;
use app\traits\PrepareOrder;
use app\traits\PreparePagination;
use PDO;

/**
 * Class BaseModel
 *
 * An abstract base model providing common CRUD operations and utilities for database interaction.
 * Designed to be extended by specific models representing database tables.
 */
abstract class BaseModel
{
    use PrepareConditions, PrepareFields, PrepareOrder, PreparePagination;

    /**
     * @var PDO $conn The PDO instance for database connection.
     */
    protected PDO $conn;

    /**
     * @var string $table The name of the table associated with the model.
     */
    protected $table;

    /**
     * BaseModel constructor.
     *
     * @param PDO $db The PDO instance for database interaction.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Retrieves the columns of the associated table.
     *
     * @return array The list of column names in the table.
     */
    private function getTableColumns(): array
    {
        $query = "DESCRIBE " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $columns ?: [];
    }

    /**
     * Retrieves all records from the table.
     *
     * @return array The list of all records as associative arrays.
     */
    public function getAll(): array
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

    /**
     * Retrieves a single record by ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @return array|null The record as an associative array or null if not found.
     */
    public function getById($id): ?array
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

    /**
     * Inserts a new record into the table.
     *
     * @param array $data An associative array of field names and values.
     * @return int The ID of the newly inserted record.
     */
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

    /**
     * Updates an existing record by ID.
     *
     * @param int $id The ID of the record to update.
     * @param array $data An associative array of field names and values to update.
     * @return bool True if the update was successful, otherwise false.
     */
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

    /**
     * Deletes a record by ID.
     *
     * @param int $id The ID of the record to delete.
     * @return bool True if the deletion was successful, otherwise false.
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
