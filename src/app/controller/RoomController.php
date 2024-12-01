<?php

namespace app\controller;

use app\models\RoomModel;

class RoomController extends BaseController
{
    public function index() : void
    {
        $roomsModel = new RoomModel($this->database);
        $rooms = $roomsModel->getAll();

        $this->jsonResponse(['status' => 'success', 'data' => $rooms]);
    }
}
