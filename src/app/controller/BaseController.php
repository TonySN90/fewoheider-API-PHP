<?php

namespace app\controller;

use JetBrains\PhpStorm\NoReturn;
use PDO;

/**
 * Class BaseController
 *
 * A base controller providing common methods for handling CRUD operations
 * and JSON responses. Designed to be extended by specific controllers.
 */
abstract class BaseController
{
    /**
     * @var PDO $database The PDO instance for database interactions.
     */
    protected PDO $database;

    /**
     * BaseController constructor.
     *
     * @param PDO $database The PDO instance for database interactions.
     */
    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Sends a JSON response and terminates the script execution.
     *
     * @param array|object $data The data to include in the JSON response.
     * @param int $statusCode The HTTP status code for the response (default: 200).
     *
     * @return void
     */
    #[NoReturn]
    protected function jsonResponse(array|object $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Retrieves all data from a model class.
     *
     * @param string $modelClass The fully qualified class name of the model.
     *
     * @return array The array of data retrieved from the model.
     */
    protected function getAllData(string $modelClass): array
    {
        $model = new $modelClass($this->database);
        return $model->getAll();
    }

    /**
     * Retrieves data by ID from a model class.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param int $id The ID of the resource to retrieve.
     *
     * @return object|null The retrieved data as an object, or null if not found.
     */
    protected function getDataById(string $modelClass, int $id): ?object
    {
        $model = new $modelClass($this->database);
        $data = $model->getById($id);

        if (is_array($data)) {
            return (object) $data;
        }

        return $data;
    }

    /**
     * Handles a request to retrieve all data and sends a JSON response.
     *
     * @param string $modelClass The fully qualified class name of the model.
     *
     * @return void
     */
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

    /**
     * Handles a request to retrieve data by ID and sends a JSON response.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param int $id The ID of the resource to retrieve.
     *
     * @return void
     */
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

    /**
     * Handles a request to create a new resource and sends a JSON response.
     *
     * @param string $modelClass The fully qualified class name of the model.
     *
     * @return void
     */
    function handleCreate(string $modelClass): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $model = new $modelClass($this->database);
        $model->create($data);
        $this->jsonResponse([
            'status' => 'success',
            'createdAt' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    /**
     * Handles a request to update an existing resource and sends a JSON response.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param int $id The ID of the resource to update.
     *
     * @return void
     */
    function handleUpdate(string $modelClass, int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $model = new $modelClass($this->database);
        $model->update($id, $data);
        $this->jsonResponse([
            'status' => 'success',
            'updatedAt' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    /**
     * Handles a request to delete a resource and sends a JSON response.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param int $id The ID of the resource to delete.
     *
     * @return void
     */
    function handleDelete(string $modelClass, int $id): void
    {
        $model = new $modelClass($this->database);
        $model->delete($id);
        $this->jsonResponse([
            'status' => 'success',
            'deletedAt' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Abstract method to retrieve all resources.
     *
     * @return void
     */
    abstract public function viewAll(): void;

    /**
     * Abstract method to retrieve a single resource by ID.
     *
     * @param int $id The ID of the resource to retrieve.
     *
     * @return void
     */
    abstract public function viewById(int $id): void;

    /**
     * Abstract method to create a new resource.
     *
     * @return void
     */
    abstract public function create(): void;

    /**
     * Abstract method to update an existing resource by ID.
     *
     * @param int $id The ID of the resource to update.
     *
     * @return void
     */
    abstract public function update(int $id): void;

    /**
     * Abstract method to delete a resource by ID.
     *
     * @param int $id The ID of the resource to delete.
     *
     * @return void
     */
    abstract public function delete(int $id): void;
}
