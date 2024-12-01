<?php

namespace app\controller;

abstract class BaseController
{
    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    abstract public function index();
}
