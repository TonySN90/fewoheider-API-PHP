<?php

namespace app\middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuthMiddleware
{
    private string $secretKey;

    /**
     * Constructor to initialize the secret key for JWT verification.
     */
    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET'];
    }

    /**
     * Middleware method to authenticate requests via JWT.
     *
     * @return bool True if the JWT is valid, false otherwise.
     */
    public function handle(): bool
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        // Check for Bearer token in Authorization header
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: Missing or invalid Authorization header']);
            return false;
        }

        $token = $matches[1];

        try {
            // Decode the token
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            // Optionally, set user ID or other claims in the session or global variable
            $_SESSION['userId'] = $decoded->sub;

            return true; // Allow the request to proceed
        } catch (Exception $e) {
            // Token decoding failed
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: Invalid token']);
            return false;
        }
    }
}
