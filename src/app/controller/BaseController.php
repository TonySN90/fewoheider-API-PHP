<?php

namespace app\controller;

use JetBrains\PhpStorm\NoReturn;
use PDO;

abstract class BaseController
{
    protected PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    #[NoReturn] protected function jsonResponse(array|object $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function getAllData(string $modelClass): array
    {
        $model = new $modelClass($this->database);
        return $model->getAll();
    }

    protected function getDataById(string $modelClass, int $id): ?object
    {
        $model = new $modelClass($this->database);
        $data = $model->getById($id);

        if (is_array($data)) {
            return (object) $data;
        }

        return $data;
    }

    protected function handleViewAll(string $modelClass): void
    {
        $data = $this->getAllData($modelClass);

        $this->jsonResponse([
            'status' => 'success',
            'results' => count($data),
            'requestAt' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    protected function handleViewById(string $modelClass, int $id): void
    {
        $data = $this->getDataById($modelClass, $id);

        if ($data) {
            $this->jsonResponse([
                'status' => 'success',
                'requestAt' => date('Y-m-d H:i:s'),
                'data' => $data
            ]);
        } else {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }
    }

    function handleCreate(string $modelClass): void {

        $data = json_decode(file_get_contents('php://input'), true);

        $model = new $modelClass($this->database);
        $model->create($data);
        $this->jsonResponse([
            'status' => 'success',
            'createdAt' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    function handleUpdate(string $modelClass, int $id): void {

        $data = json_decode(file_get_contents('php://input'), true);

        $model = new $modelClass($this->database);
        $model->update($id, $data);
        $this->jsonResponse([
            'status' => 'success',
            'updatedAt' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    function handleDelete(string $modelClass, int $id): void {

        $model = new $modelClass($this->database);
        $model->delete($id);
        $this->jsonResponse([
            'status' => 'success',
            'deletedAt' => date('Y-m-d H:i:s')
        ]);
    }

    abstract public function viewAll(): void;
    abstract public function viewById(int $id): void;

    abstract public function create(): void;

    abstract public function update(int $id): void;

    abstract public function delete(int $id): void;
}



