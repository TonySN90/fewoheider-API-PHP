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

    public function create(): void
    {
        $this->handleCreate(GuestModel::class);
    }

    public function update(int $id): void
    {
        $this->handleUpdate(GuestModel::class, $id);
    }

    public function delete(int $id): void
    {
        $this->handleDelete(GuestModel::class, $id);
    }
}
