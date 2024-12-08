<?php

namespace app\controller;

use app\models\BookingModel;

class BookingController extends BaseController
{
    public function getAll(): void
    {
        $this->handleGetAllData(BookingModel::class);
    }

    public function getById(int $id): void
    {
        $this->handleGetDataById(BookingModel::class, $id);
    }

    public function create(): void
    {
        $this->handleCreate(BookingModel::class);
    }

    public function update(int $id): void
    {
        $this->handleUpdate(BookingModel::class, $id);
    }

    public function delete(int $id): void
    {
        $this->handleDelete(BookingModel::class, $id);
    }
}

