<?php

namespace app\traits;

trait PrepareFields
{
    private function prepareFields(): string
    {
        if (empty($_GET['field'])) {
            return "*";
        }

        $requestedFields = explode(',', $_GET['field']);
        $sanitizedFields = array_map(fn($field) => preg_replace('/[^a-zA-Z0-9_]/', '', $field), $requestedFields);

        $validFields = $this->getTableColumns();
        $invalidFields = array_diff($sanitizedFields, $validFields);

        if (!empty($invalidFields)) {
            http_response_code(404);
            echo json_encode([
                "error" => "Invalid fields",
                "invalid_fields" => array_values($invalidFields),
                "message" => "One or more fields do not exist in the table."
            ]);
            exit;
        }

        return implode(', ', $sanitizedFields);
    }
}
