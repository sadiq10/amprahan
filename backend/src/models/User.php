<?php

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getByUsername($username) {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password_hash, $role) {
        $query = 'INSERT INTO ' . $this->table_name . ' (username, password_hash, role) VALUES (:username, :password_hash, :role)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
