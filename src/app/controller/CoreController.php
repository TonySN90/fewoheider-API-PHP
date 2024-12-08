<?php

namespace app\controller;

use JetBrains\PhpStorm\NoReturn;
use PDO;

/**

 * Class CoreController
 */

abstract class CoreController
{
    /**
     * @var PDO $database The PDO instance for database interactions.
     */
    protected PDO $database;

    /**
     * CoreController constructor.
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
     * Decodes JSON input from the request body.
     *
     * @return array The decoded data.
     */
    protected function getRequestData(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
