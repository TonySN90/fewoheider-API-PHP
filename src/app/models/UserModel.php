<?php

namespace app\models;

use PDO;

/**
 * UserModel
 *
 * A model that handles user-specific database operations.
 * It extends the functionality of the BaseModel to fulfill user-related requirements.
 */
class UserModel extends BaseModel
{
    /**
     * @var PDO $db The PDO instance for database interaction.
     */
    protected PDO $db;

    /**
     * Constructor for the UserModel.
     *
     * @param PDO $db The PDO instance for database interaction.
     */
    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @param string $email The email address of the new user.
     * @param string $verificationToken The verification token for the new user.
     *
     * @return bool Returns true if the user was successfully created, otherwise false.
     */
    public function createUser(string $username, string $password, string $email, string $verificationToken): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email, verification_token, is_verified) 
                                    VALUES (:username, :password, :email, :verification_token, 0)"); // User is initially not verified
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email' => $email,
            ':verification_token' => $verificationToken
        ]);
    }

    /**
     * Retrieves a user by their username.
     *
     * @param string $username The username of the user to retrieve.
     *
     * @return array|null Returns an associative array of the user's data if found, or null if not found.
     */
    public function getUserByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
