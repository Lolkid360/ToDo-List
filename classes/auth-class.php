<?php
require_once 'db-config.php';

class UserAuth {
    private $conn;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function signup($username, $email, $password) {
        // Check if username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = :username OR email = :email";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute(['username' => $username, 'email' => $email]);
        
        if ($checkStmt->rowCount() > 0) {
            return false; // User already exists
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user
        $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);
        
        try {
            $result = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ]);
            return $result;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['username' => $username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
}