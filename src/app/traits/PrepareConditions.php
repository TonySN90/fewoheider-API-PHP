<?php

namespace app\traits;

/**
 * Trait PrepareConditions
 *
 * Provides functionality to generate SQL conditions dynamically based on query parameters.
 */
trait PrepareConditions
{
    /**
     * Prepares SQL conditions and their corresponding bind parameters.
     *
     * This method analyzes the GET parameters from the request, excluding keys used for sorting
     * (`field`, `order`) and pagination (`limit`, `offset`). It constructs a SQL `WHERE` clause and
     * a set of bind parameters for a prepared statement.
     *
     * @return array Contains two elements:
     *               - string: The SQL `WHERE` clause (e.g., "WHERE column1 = :column1 AND column2 = :column2").
     *               - array:  The bind parameters for the prepared statement
     *                         (e.g., [':column1' => value1, ':column2' => value2]).
     */
    private function prepareConditions(): array
    {
        $params = $_GET; // Retrieve query parameters from the request.
        $conditions = []; // Array to hold SQL conditions.
        $bindParams = []; // Array to hold bind parameters.

        foreach ($params as $key => $value) {
            // Exclude keys used for sorting or pagination, and only process non-empty values.
            if (!empty($value) && $key !== 'field' && $key !== 'order' && $key !== 'limit' && $key !== 'offset') {
                // Add condition and bind parameter for the current key.
                $conditions[] = "`$key` = :$key";
                $bindParams[":$key"] = $value;
            }
        }

        // Construct the WHERE clause if there are conditions.
        $queryPart = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

        return [$queryPart, $bindParams];
    }
}
