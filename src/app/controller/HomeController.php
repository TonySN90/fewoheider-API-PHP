<?php

namespace app\controller;

use app\models\BookingModel;

class HomeController extends BaseController
{
    public function index()
    {
        $this->jsonResponse(['status' => 'success', 'data' => "Fewo Heider"]);
    }
}