<?php

namespace app\controller;

use app\models\GuestModel;

class GuestController extends BaseController
{
    public function index() : void
    {
        $guestsModel = new GuestModel($this->database);
        $guests = $guestsModel->getAll();

        $this->jsonResponse(['status' => 'success', 'data' => $guests]);
    }
}
