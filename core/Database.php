<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    public $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Eroare conexiune BD: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8");
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }

    public function close() {
        $this->conn->close();
    }
}