<?php
require_once __DIR__ . '/../config/config.php';

class UserController {

    // Inscription
    public static function register() {
        $nom = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validation
        if (empty($nom) || empty($email) || empty($password)) {
            Flight::redirect('/signup?error=missing_fields');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::redirect('/signup?error=invalid_email');
            return;
        }

        if (strlen($password) < 6) {
            Flight::redirect('/signup?error=password_too_short');
            return;
        }

        try {
            $pdo = getDB();
            
            // Vérifier si l'email existe déjà
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                Flight::redirect('/signup?error=email_exists');
                return;
            }

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur
            $stmt = $pdo->prepare(
                "INSERT INTO user (nom, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())"
            );
            $stmt->execute([$nom, $email, $hashedPassword]);

            // Rediriger vers login avec message de succès
            Flight::redirect('/login?success=registered');
            
        } catch (PDOException $e) {
            error_log("Erreur d'inscription: " . $e->getMessage());
            Flight::redirect('/signup?error=db_error');
        }
    }

    // Tableau de bord utilisateur
    public static function dashboard() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            Flight::redirect('/login');
            return;
        }
        
        // Dashboard simple
        echo "<!DOCTYPE html>";
        echo "<html><head><title>Dashboard Utilisateur</title>";
        echo "<style>
            body{font-family:Arial;margin:40px;background:#f6f8fb}
            .container{max-width:800px;margin:0 auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 6px 30px rgba(19,38,92,0.12)}
            .header{display:flex;align-items:center;justify-content:space-between;background:#13265C;color:#fff;padding:18px 30px;border-radius:12px 12px 0 0;}
            .logo{font-size:28px;font-weight:bold;letter-spacing:2px;}
            .logo span{color:#27ae60;}
            .user-menu{display:flex;align-items:center;gap:18px;}
            .user-name{font-weight:600;}
            .avatar{background:#27ae60;color:#fff;width:38px;height:38px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:18px;font-weight:bold;}
            .btn-logout{background:#fff;color:#13265C;padding:7px 16px;border-radius:999px;text-decoration:none;font-weight:bold;font-size:18px;transition:background 0.2s;}
            .btn-logout:hover{background:#e0e4e9;}
            .menu{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin:30px 0}
            .menu a{display:block;padding:20px;background:#f0f2f5;text-align:center;color:#13265C;text-decoration:none;border-radius:8px;font-weight:bold}
            .menu a:hover{background:#e0e4e9}
        </style></head><body>";
        echo "<div class='container'>";
        echo '<header class="header">
            <div class="logo">
                BNG<span>RC</span>
            </div>
            <div class="user-menu">
                <span class="user-name">' . htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur') . '</span>
                <div class="avatar">';
        $initiales = '';
        if (isset($_SESSION['nom'])) {
            $nom = $_SESSION['nom'];
            $mots = explode(' ', $nom);
            foreach ($mots as $mot) {
                $initiales .= strtoupper(substr($mot, 0, 1));
            }
        } else {
            $initiales = 'U';
        }
        echo substr($initiales, 0, 2);
        echo '</div>
                <a href="' . $base . '/logout" class="btn-logout">↪</a>
            </div>
        </header>';
        
        echo "<div class='menu'>";
        echo "<a href='/user/besoins'>Voir les besoins</a>";
        echo "<a href='/user/dons'>Faire un don</a>";
        echo "<a href='/user/villes'>Statistiques par ville</a>";
        echo "</div>";
        
        echo "<p><a href='/logout' class='logout'>Déconnexion</a></p>";
        echo "</div></body></html>";
    }

    // Déconnexion
    public static function logout() {
        session_unset();
        session_destroy();
        Flight::redirect('/login');
    }
}
?>