<?php

namespace app\controller;

use app\models\GuestModel;

class GuestController extends BaseController
{
    public function getAll(): void
    {
        $this->handleGetAllData(GuestModel::class);
    }

    public function getById(int $id): void
    {
        $this->handleGetDataById(GuestModel::class, $id);
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
