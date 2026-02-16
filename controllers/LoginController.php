<?php
require_once __DIR__ . '/../config/config.php';

class LoginController {
    
    public static function authenticate($email, $password) {
        try {
            $pdo = getDB();
            
            // Chercher l'utilisateur par email
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier si l'utilisateur existe
            if (!$user) {
                error_log("Login failed: User not found with email: $email");
                return false;
            }
            
            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // Stocker les informations en session
                $_SESSION['user'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                error_log("Login successful for: " . $email);
                return true;
            } else {
                error_log("Login failed: Invalid password for: " . $email);
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    // Méthode pour vérifier si l'utilisateur est connecté
    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }
    
    // Méthode pour obtenir l'utilisateur courant
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id, nom, email, role FROM user WHERE id = ?");
            $stmt->execute([$_SESSION['user']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting current user: " . $e->getMessage());
            return null;
        }
    }
}
?>