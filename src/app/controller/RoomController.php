<?php

namespace app\controller;

use app\models\RoomModel;

class RoomController extends BaseController
{
    public function getAll(): void
    {
        $this->handleGetAllData(RoomModel::class);
    }

    public function getById(int $id): void
    {
        $this->handleGetDataById(RoomModel::class, $id);
    }

    public function create(): void
    {
        $this->handleCreate(RoomModel::class);
    }

    public function update(int $id): void
    {
        $this->handleUpdate(RoomModel::class, $id);
    }

    public function delete(int $id): void
    {
        $this->handleDelete(RoomModel::class, $id);
    }
}
