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

    #[NoReturn] protected function jsonResponse(array|object $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    abstract public function index(): void;
}

