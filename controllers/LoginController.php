<?php
require_once __DIR__ . '/../config/config.php';

class LoginController {
    public static function authenticate($email, $password) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['email'];
                $_SESSION['role'] = $user['role']; // stocker le rôle
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Erreur authentification: " . $e->getMessage());
            return false;
        }
    }
}

