<?php

namespace app\traits;

/**
 * Trait PreparePagination
 *
 * Provides functionality to dynamically add pagination to SQL queries based on user-supplied parameters.
 */
trait PreparePagination
{
    /**
     * Prepares the LIMIT and OFFSET clauses for an SQL query based on the `limit` and `offset` parameters in the request.
     *
     * - `limit` specifies the maximum number of rows to return.
     * - `offset` specifies the starting point for the rows to return (optional).
     *
     * @return string The LIMIT and OFFSET clause of the query, or an empty string if no pagination parameters are specified.
     */
    private function preparePagination(): string
    {
        // Extract pagination parameters from the request.
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : null;

        // If a limit is provided, construct the LIMIT clause with an optional OFFSET.
        if ($limit) {
            return " LIMIT $limit" . ($offset ? " OFFSET $offset" : "");
        }

        // Return an empty string if no pagination parameters are provided.
        return "";
    }
}
