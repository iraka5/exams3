<?php
require_once __DIR__ . '/../config/config.php';

class UserController {

    // Affiche le formulaire d'inscription
    public static function registerForm() {
        include __DIR__ . '/../views/signup.html';
    }

    // Affiche le formulaire de connexion utilisateur (si besoin séparé)
    public static function loginForm() {
        include __DIR__ . '/../views/login.html';
    }

    // Inscription
    public static function register() {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            Flight::redirect('/exams3-main/exams3/signup?error=missing_fields');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::redirect('/exams3-main/exams3/signup?error=invalid_email');
            return;
        }

        if (strlen($password) < 6) {
            Flight::redirect('/exams3-main/exams3/signup?error=weak_password');
            return;
        }

        try {
            $pdo = getDB();

            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                Flight::redirect('/exams3-main/exams3/signup?error=email_exists');
                return;
            }

            // Créer l'utilisateur
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$username, $email, $hashedPassword]);

            // Rediriger vers login
            Flight::redirect('/exams3-main/exams3/login?success=registered');

        } catch (Exception $e) {
            error_log("Erreur inscription: " . $e->getMessage());
            Flight::redirect('/exams3-main/exams3/signup?error=database');
        }
    }

    // Authentification utilisateur
    public static function authenticate() {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Flight::redirect('/exams3-main/exams3/login?error=missing_fields');
            return;
        }

        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'user'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                Flight::redirect('/exams3-main/exams3/user/dashboard');
            } else {
                Flight::redirect('/exams3-main/exams3/login?error=invalid_credentials');
            }

        } catch (Exception $e) {
            error_log("Erreur connexion utilisateur: " . $e->getMessage());
            Flight::redirect('/exams3-main/exams3/login?error=database');
        }
    }

    // Tableau de bord utilisateur
    public static function dashboard() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            Flight::redirect('/exams3-main/exams3/login');
            return;
        }
        include __DIR__ . '/../views/users/dashboard.php';
    }

    // Déconnexion
    public static function logout() {
        session_destroy();
        Flight::redirect('/exams3-main/exams3/login?success=logged_out');
    }
}
