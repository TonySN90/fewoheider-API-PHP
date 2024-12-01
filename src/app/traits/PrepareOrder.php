<?php

namespace app\traits;

trait PrepareOrder
{
    private function prepareOrder(): string
    {
        $order = $_GET['order'] ?? null;

        if ($order) {
            $validFields = $this->getTableColumns();
            $orderParts = explode(':', $order);

            if (count($orderParts) === 2) {
                $field = $orderParts[0];
                $direction = strtoupper($orderParts[1]);

                if (!in_array($field, $validFields) || !in_array($direction, ['ASC', 'DESC'])) {
                    http_response_code(400);
                    echo json_encode(["error" => "Invalid order parameter"]);
                    exit;
                }

                return " ORDER BY $field $direction";
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Invalid order format. Use 'field:direction'."]);
                exit;
            }
        }

        return "";
    }
}

