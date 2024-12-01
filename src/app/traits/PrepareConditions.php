<?php

namespace app\traits;

trait PrepareConditions
{
    private function prepareConditions(): array
    {
        $params = $_GET;
        $conditions = [];
        $bindParams = [];

        foreach ($params as $key => $value) {
            if (!empty($value) && $key !== 'field' && $key !== 'order' && $key !== 'limit' && $key !== 'offset') {
                $conditions[] = "`$key` = :$key";
                $bindParams[":$key"] = $value;
            }
        }

        $queryPart = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";
        return [$queryPart, $bindParams];
    }

}
