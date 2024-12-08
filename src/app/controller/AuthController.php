<?php

namespace app\controller;
use Firebase\JWT\JWT;
use app\models\UserModel;
use PDO;
use app\traits\EmailVerification;

class AuthController extends CoreController
{

    use EmailVerification;
    private string $jwtSecret;

    public function __construct(PDO $database)
    {
        parent::__construct($database);
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }

    public function verifyAccount(): void
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            $this->jsonResponse(['error' => 'Verification token is missing'], 400);
        }

        $userModel = new UserModel($this->database);
        $user = $userModel->getUserByVerificationToken($token);

        if (!$user) {
            $this->jsonResponse(['error' => 'Invalid or expired verification token'], 400);
        }

        if ($userModel->verifyUser($token)) { // Verwende den Token direkt
            $this->jsonResponse(['message' => 'Account successfully verified']);
        } else {
            $this->jsonResponse(['error' => 'Failed to verify account'], 500);
        }

    }


    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $email = $data['email'] ?? null;

        if (!$username || !$password || !$email) {
            $this->jsonResponse(['error' => 'All fields are required'], 400);
        }

        $userModel = new UserModel($this->database);
        if ($userModel->getUserByUsername($username) || $userModel->getUserByEmail($email)) {
            $this->jsonResponse(['error' => 'Username or email already exists'], 400);
        }

        $verificationToken = bin2hex(random_bytes(16));
        if ($userModel->createUser($username, $password, $email, $verificationToken)) {
            $this->sendVerificationEmail($email, $verificationToken);
            $this->jsonResponse(['message' => 'User registered successfully. Please check your email for verification.']);
        } else {
            $this->jsonResponse(['error' => 'Failed to register user'], 500);
        }
    }




    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            $this->jsonResponse(['error' => 'Username and password are required'], 400);
        }

        $userModel = new UserModel($this->database);
        $user = $userModel->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->jsonResponse(['error' => 'Invalid credentials'], 401);
        }

        // Generate JWT Token
        $payload = [
            'iss' => 'fewo_heider-api',  // Issuer
            'sub' => $user['id'],        // User ID
            'iat' => time(),             // Issued At
            'exp' => time() + 86400      // Expiration Time (1 day)
        ];

        $token = JWT::encode($payload, $this->jwtSecret, 'HS256');
        $this->jsonResponse(['token' => $token]);
    }

}
