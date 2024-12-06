<?php

namespace app\traits;

/**
 * Trait PrepareFields
 *
 * Provides functionality to dynamically select specific fields in an SQL query.
 */
trait PrepareFields
{
    /**
     * Prepares a sanitized and validated list of fields for an SQL SELECT query.
     *
     * This method uses the `field` parameter from the request (`$_GET['field']`) to determine
     * which fields should be retrieved from the database. It ensures that:
     * - Only valid fields (matching table columns) are included.
     * - Invalid fields are rejected with an error response.
     *
     * @return string A comma-separated list of sanitized and validated field names, or '*' for all fields.
     */
    private function prepareFields(): string
    {
        // If no specific fields are requested, select all columns.
        if (empty($_GET['field'])) {
            return "*";
        }

        // Split the requested fields by comma and sanitize them to allow only valid characters.
        $requestedFields = explode(',', $_GET['field']);
        $sanitizedFields = array_map(fn($field) => preg_replace('/[^a-zA-Z0-9_]/', '', $field), $requestedFields);

        // Get the list of valid columns from the database table.
        $validFields = $this->getTableColumns();

        // Identify invalid fields (those not present in the table).
        $invalidFields = array_diff($sanitizedFields, $validFields);

        // If there are invalid fields, return an error response and stop execution.
        if (!empty($invalidFields)) {
            http_response_code(404);
            echo json_encode([
                "error" => "Invalid fields",
                "invalid_fields" => array_values($invalidFields),
                "message" => "One or more fields do not exist in the table."
            ]);
            exit;
        }

        // Return a comma-separated list of valid fields.
        return implode(', ', $sanitizedFields);
    }
}
