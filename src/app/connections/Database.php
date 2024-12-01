<?php

namespace app\connections;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'fewo_heider';
    private $username = 'Tony';
    private $password = 'ged33njv';
    public $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Datenbankverbindung fehlgeschlagen: " . $e->getMessage();
            exit;
        }
        return $this->conn;
    }
}
