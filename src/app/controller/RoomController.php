<?php

namespace app\controller;

use app\models\RoomModel;

class RoomController extends BaseController
{
    public function viewAll(): void
    {
        $this->handleViewAll(RoomModel::class);
    }

    public function viewById(int $id): void
    {
        $this->handleViewById(RoomModel::class, $id);
    }
}
