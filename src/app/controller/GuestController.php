<?php

namespace app\controller;

use app\models\GuestModel;

class GuestController extends BaseController
{
    public function viewAll(): void
    {
        $this->handleViewAll(GuestModel::class);
    }

    public function viewById(int $id): void
    {
        $this->handleViewById(GuestModel::class, $id);
    }
}
