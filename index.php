<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/UserController.php';

// Récupérer l'URL demandée
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Enlever le chemin de base
$base = '/exams3-main/exams3';
if (strpos($request, $base) === 0) {
    $path = substr($request, strlen($base));
} else {
    $path = $request;
}

// Enlever les paramètres GET
$path = strtok($path, '?');

// Router simple
switch ($path) {
    case '/':
    case '':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header('Location: ' . $base . '/tableau-bord');
        } else {
            header('Location: ' . $base . '/user/dashboard');
        }
        break;
        
    // LOGIN
    case '/login':
        if ($method === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (LoginController::authenticate($email, $password)) {
                if ($_SESSION['role'] === 'admin') {
                    header('Location: ' . $base . '/tableau-bord');
                } else {
                    header('Location: ' . $base . '/user/dashboard');
                }
                exit;
            } else {
                header('Location: ' . $base . '/login?error=invalid');
                exit;
            }
        } else {
            // Inclure le fichier HTML
            $loginFile = __DIR__ . '/views/login.html';
            if (file_exists($loginFile)) {
                include $loginFile;
            } else {
                echo "Fichier login.html non trouvé dans: " . __DIR__ . '/views/';
            }
        }
        break;
        
    // SIGNUP
    case '/signup':
        if ($method === 'POST') {
            // Traitement de l'inscription
            $nom = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            if (empty($nom) || empty($email) || empty($password)) {
                header('Location: ' . $base . '/signup?error=missing_fields');
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: ' . $base . '/signup?error=invalid_email');
                exit;
            }
            
            if (strlen($password) < 6) {
                header('Location: ' . $base . '/signup?error=password_too_short');
                exit;
            }
            
            try {
                $pdo = getDB();
                
                // Vérifier si l'email existe
                $check = $pdo->prepare("SELECT id FROM user WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetch()) {
                    header('Location: ' . $base . '/signup?error=email_exists');
                    exit;
                }
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user (nom, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
                $stmt->execute([$nom, $email, $hashedPassword]);
                
                header('Location: ' . $base . '/login?success=registered');
                exit;
                
            } catch (Exception $e) {
                header('Location: ' . $base . '/signup?error=db_error');
                exit;
            }
        } else {
            // Inclure le fichier HTML
            $signupFile = __DIR__ . '/views/signup.html';
            if (file_exists($signupFile)) {
                include $signupFile;
            } else {
                echo "Fichier signup.html non trouvé dans: " . __DIR__ . '/views/';
            }
        }
        break;
        
    // USER DASHBOARD
    case '/user/dashboard':
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            header('Location: ' . $base . '/login');
            exit;
        }
        echo "<h1>Dashboard Utilisateur</h1>";
        echo "<p>Bienvenue " . ($_SESSION['nom'] ?? '') . " !</p>";
        echo "<a href='" . $base . "/user/besoins'>Voir les besoins</a><br>";
        echo "<a href='" . $base . "/user/dons'>Faire un don</a><br>";
        echo "<a href='" . $base . "/user/villes'>Statistiques</a><br>";
        echo "<a href='" . $base . "/logout'>Déconnexion</a>";
        break;
        
    // LOGOUT
    case '/logout':
        session_destroy();
        header('Location: ' . $base . '/login');
        exit;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>Chemin demandé: $path</p>";
        echo "<a href='" . $base . "'>Accueil</a>";
        break;
}
?>