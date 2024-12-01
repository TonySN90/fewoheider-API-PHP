<?php

namespace app\controller;

class HomeController extends BaseController
{
    public function index() : void
    {
        $this->jsonResponse(['status' => 'success', 'data' => "Fewo Heider"]);
    }
}