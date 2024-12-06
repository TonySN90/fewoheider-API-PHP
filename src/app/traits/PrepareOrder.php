<?php

namespace app\traits;

/**
 * Trait PrepareOrder
 *
 * Provides functionality to dynamically sort query results based on user-supplied parameters.
 */
trait PrepareOrder
{
    /**
     * Prepares the ORDER BY clause for an SQL query based on the `order` parameter in the request.
     *
     * The `order` parameter should follow the format `field:direction`:
     * - `field` is the name of a column in the table.
     * - `direction` is either `ASC` (ascending) or `DESC` (descending).
     *
     * @return string The ORDER BY clause of the query, or an empty string if no valid order is specified.
     */
    private function prepareOrder(): string
    {
        // Get the 'order' parameter from the request.
        $order = $_GET['order'] ?? null;

        if ($order) {
            // Retrieve the list of valid table columns.
            $validFields = $this->getTableColumns();
            $orderParts = explode(':', $order);

            // Check if the order format is correct (`field:direction`).
            if (count($orderParts) === 2) {
                $field = $orderParts[0];
                $direction = strtoupper($orderParts[1]);

                // Validate the field name and the sorting direction.
                if (!in_array($field, $validFields) || !in_array($direction, ['ASC', 'DESC'])) {
                    http_response_code(400);
                    echo json_encode(["error" => "Invalid order parameter"]);
                    exit;
                }

                // Return the prepared ORDER BY clause.
                return " ORDER BY $field $direction";
            } else {
                // Handle incorrect format for the order parameter.
                http_response_code(400);
                echo json_encode(["error" => "Invalid order format. Use 'field:direction'."]);
                exit;
            }
        }

        // Return an empty string if no order parameter is provided.
        return "";
    }
}
