<?php

namespace app\connections;

use PDO;
use PDOException;

class Database
{
    private string $host = 'localhost';
    private string $db_name = 'fewo_heider';
    private string $username = 'Tony';
    private string $password = 'ged33njv';
    private ?PDO $conn = null;
    private static ?Database $instance = null;

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->conn;
    }

    private function connect(): void
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "database connection failed: " . $e->getMessage();
            exit;
        }
    }

    private function __clone() {}

    public function __wakeup() {}
}
