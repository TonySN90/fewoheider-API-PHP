<?php

namespace app\controller;

use app\models\GuestModel;

class GuestsController extends BaseController
{
    public function index()
    {
        $guestsModel = new GuestModel($this->database);
        $guests = $guestsModel->getAll();

        $this->jsonResponse(['status' => 'success', 'data' => $guests]);
    }
}
