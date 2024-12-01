<?php

namespace app\controller;

use app\models\BookingModel;

class BookingController extends BaseController
{
    public function viewAll(): void
    {
        $this->handleViewAll(BookingModel::class);
    }

    public function viewById(int $id): void
    {
        $this->handleViewById(BookingModel::class, $id);
    }
}

