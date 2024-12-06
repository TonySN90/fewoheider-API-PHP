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

