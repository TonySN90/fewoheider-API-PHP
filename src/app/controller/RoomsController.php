<?php

namespace app\controller;

use app\models\RoomModel;

class RoomsController extends BaseController
{
    public function index()
    {
        $roomsModel = new RoomModel($this->database);
        $rooms = $roomsModel->getAll();

        $this->jsonResponse(['status' => 'success', 'data' => $rooms]);
    }
}
