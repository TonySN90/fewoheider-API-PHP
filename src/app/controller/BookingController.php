<?php

namespace app\controller;

use app\models\BookingModel;

class BookingController extends BaseController
{
    public function index() : void
    {
        $bookingModel = new BookingModel($this->database);
        $bookings = $bookingModel->getAll();

        $this->jsonResponse(['status' => 'success', 'data' => $bookings]);
    }
}
