<?php
require_once __DIR__ . '/../config/config.php';

class UserController {
    
    public static function registerForm() {
        include __DIR__ . '/../views/users/register.php';
    }
    
    public static function loginForm() {
        include __DIR__ . '/../views/users/login.php';
    }
    
    public static function register() {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Validation
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            Flight::redirect('/exams3-main/exams3/user/register?error=missing_fields');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::redirect('/exams3-main/exams3/user/register?error=invalid_email');
            return;
        }
        
        if (strlen($password) < 6) {
            Flight::redirect('/exams3-main/exams3/user/register?error=weak_password');
            return;
        }
        
        try {
            $pdo = getDB();
            
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                Flight::redirect('/exams3-main/exams3/user/register?error=email_exists');
                return;
            }
            
            // Créer l'utilisateur
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $username = strtolower($prenom . '_' . $nom);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, nom, prenom, email, password, role, password_used) VALUES (?, ?, ?, ?, ?, 'user', FALSE)");
            $stmt->execute([$username, $nom, $prenom, $email, $hashedPassword]);
            
            Flight::redirect('/exams3-main/exams3/user/login?success=registered');
            
        } catch (Exception $e) {
            error_log("Erreur inscription: " . $e->getMessage());
            Flight::redirect('/exams3-main/exams3/user/register?error=database');
        }
    }
    
    public static function authenticate() {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($email) || empty($password)) {
            Flight::redirect('/exams3-main/exams3/user/login?error=missing_fields');
            return;
        }
        
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'user'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                
                // Marquer le mot de passe comme utilisé (une seule utilisation)
                if ($user['password_used']) {
                    Flight::redirect('/exams3-main/exams3/user/login?error=password_used');
                    return;
                }
                
                // Marquer le mot de passe comme utilisé
                $updateStmt = $pdo->prepare("UPDATE users SET password_used = TRUE WHERE id = ?");
                $updateStmt->execute([$user['id']]);
                
                // Enregistrer la connexion
                self::logConnection($user['id'], $email);
                
                // Définir la session
                $_SESSION['user'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                $_SESSION['role'] = $user['role'];
                
                Flight::redirect('/exams3-main/exams3/user/dashboard');
                
            } else {
                Flight::redirect('/exams3-main/exams3/user/login?error=invalid_credentials');
            }
            
        } catch (Exception $e) {
            error_log("Erreur connexion utilisateur: " . $e->getMessage());
            Flight::redirect('/exams3-main/exams3/user/login?error=database');
        }
    }
    
    private static function logConnection($user_id, $email) {
        try {
            $pdo = getDB();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            
            $stmt = $pdo->prepare("INSERT INTO user_connections (user_id, email, ip_address, user_agent) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $email, $ip, $userAgent]);
        } catch (Exception $e) {
            error_log("Erreur log connexion: " . $e->getMessage());
        }
    }
    
    public static function dashboard() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            Flight::redirect('/exams3-main/exams3/user/login');
            return;
        }
        
        include __DIR__ . '/../views/users/dashboard.php';
    }
    
    public static function logout() {
        session_destroy();
        Flight::redirect('/exams3-main/exams3/user/login?success=logged_out');
    }
}