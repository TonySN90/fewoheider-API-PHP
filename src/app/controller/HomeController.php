<?php

namespace app\controller;

class HomeController
{
    public function index() : void
    {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'requestAt' => date('Y-m-d H:i:s'),
            'message' => "API ready"
        ]);
    }

}