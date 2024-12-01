<?php

namespace app\controller;

use app\models\GuestModel;

class GuestController extends BaseController
{
    public function index() : void
    {
        $guestsModel = new GuestModel($this->database);
        $guests = $guestsModel->getAll();

        $this->jsonResponse([
            'status' => 'success',
            'results' => count($guests),
            'requestAt' => date('Y-m-d H:i:s'),
            'data' => $guests]);
    }
}
