<?php

namespace app\traits;

trait PreparePagination
{
    private function preparePagination(): string
    {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : null;

        if ($limit) {
            return " LIMIT $limit" . ($offset ? " OFFSET $offset" : "");
        }

        return "";
    }
}
