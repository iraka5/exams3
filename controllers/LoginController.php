<?php
require_once __DIR__ . '/../config/database.php';

class LoginController {
    public static function authenticate($email, $password) {
        // Exemple avec PDO
        $pdo = new PDO("mysql:host=localhost;dbname=exams3", "root", "");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['email'];
            $_SESSION['role'] = $user['role']; // stocker le rôle
            return true;
        }
        return false;
    }
}

