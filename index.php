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
        
    // DASHBOARD V2
    case '/dashboard-v2':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once __DIR__ . '/controllers/DashboardController.php';
        DashboardController::dashboardV2();
        break;
        
    // API DASHBOARD V2
    case '/api/dashboard-v2':
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }
        require_once __DIR__ . '/controllers/DashboardController.php';
        DashboardController::apiDashboardV2();
        break;
        
    // API VERIFICATION FONDS
    case '/api/verifier-fonds':
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }
        require_once __DIR__ . '/controllers/DashboardController.php';
        DashboardController::apiVerifierFonds();
        break;
        
    // LOGOUT
    case '/logout':
        session_destroy();
        header('Location: ' . $base . '/login');
        exit;
        
    // ===== ROUTES AJOUTÉES POUR RÉSOUDRE LES ERREURS 404 =====
    
    // Routes pour les régions
    case '/regions':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/RegionController.php';
        $controller = new RegionController();
        $controller->index();
        break;
        
    case '/regions/create':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/RegionController.php';
        $controller = new RegionController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
        
    case '/regions/edit':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/RegionController.php';
        $controller = new RegionController();
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id);
        } else {
            $controller->edit($id);
        }
        break;
        
    case '/regions/show':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/RegionController.php';
        $controller = new RegionController();
        $id = $_GET['id'] ?? null;
        $controller->show($id);
        break;
        
    // Routes pour les villes
    case '/villes':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/VilleController.php';
        $controller = new VilleController();
        $controller->index();
        break;
        
    case '/villes/create':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/VilleController.php';
        $controller = new VilleController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
        
    case '/villes/edit':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/VilleController.php';
        $controller = new VilleController();
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id);
        } else {
            $controller->edit($id);
        }
        break;
        
    case '/villes/show':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/VilleController.php';
        $controller = new VilleController();
        $id = $_GET['id'] ?? null;
        $controller->show($id);
        break;
        
    // Routes pour les besoins
    case '/besoins':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/BesoinController.php';
        $controller = new BesoinController();
        $controller->index();
        break;
        
    case '/besoins/create':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/BesoinController.php';
        $controller = new BesoinController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
        
    case '/besoins/edit':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/BesoinController.php';
        $controller = new BesoinController();
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id);
        } else {
            $controller->edit($id);
        }
        break;
        
    case '/besoins/show':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/BesoinController.php';
        $controller = new BesoinController();
        $id = $_GET['id'] ?? null;
        $controller->show($id);
        break;
        
    // Routes pour les dons
    case '/dons':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/DonController.php';
        $controller = new DonController();
        $controller->index();
        break;
        
    case '/dons/create':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/DonController.php';
        $controller = new DonController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
        
    case '/dons/edit':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/DonController.php';
        $controller = new DonController();
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($id);
        } else {
            $controller->edit($id);
        }
        break;
        
    case '/dons/show':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/DonController.php';
        $controller = new DonController();
        $id = $_GET['id'] ?? null;
        $controller->show($id);
        break;
        
    // Routes pour les achats
    case '/achats':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/AchatController.php';
        $controller = new AchatController();
        $controller->index();
        break;
        
    case '/achats/create':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/AchatController.php';
        $controller = new AchatController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;
        
    case '/achats/recapitulatif':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'controllers/AchatController.php';
        $controller = new AchatController();
        $controller->recapitulatif();
        break;
        
    // Page de test (sans authen)
    case '/test':
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Test BNGRC - Routing</title>
    <link rel='stylesheet' href='/public/css/styles.css'>
</head>
<body>
    <div class='container'>
        <div class='card slide-up'>
            <div class='card-header'>
                <h1>✅ Test du Routing BNGRC</h1>
                <p>Si vous voyez cette page, le routing fonctionne correctement!</p>
            </div>
            <div class='card-body'>
                <h3>Pages disponibles:</h3>
                <div class='grid grid-2 mt-4'>
                    <a href='/dashboard' class='btn btn-primary'>🏠 Dashboard</a>
                    <a href='/regions/create' class='btn btn-success'>🌍 Créer région</a>
                    <a href='/villes/create' class='btn btn-info'>🏢 Créer ville</a>
                    <a href='/besoins/create' class='btn btn-warning'>📋 Créer besoin</a>
                    <a href='/dons/create' class='btn btn-secondary'>🎁 Créer don</a>
                    <a href='/dashboard-v2' class='btn btn-primary'>📊 Dashboard V2</a>
                </div>
                <div class='mt-4 p-3' style='background: #f8fafc; border-radius: 0.5rem;'>
                    <h4>Debug info:</h4>
                    <ul class='text-sm text-secondary'>
                        <li>PHP Version: " . PHP_VERSION . "</li>
                        <li>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</li>
                        <li>Request URI: " . $_SERVER['REQUEST_URI'] . "</li>
                        <li>Path: $path</li>
                        <li>Session: " . (isset($_SESSION['user_id']) ? 'Connecté ID=' . $_SESSION['user_id'] : 'Non connecté') . "</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
        break;
        
    // Dashboard principal
    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once 'views/tableau_bord_simple.php';
        break;
        
    // Connexion automatique de test
    case '/auto-login':
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'Utilisateur Test';
        $_SESSION['role'] = 'admin';
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Auto-Login - BNGRC</title>
    <link rel='stylesheet' href='/public/css/styles.css'>
    <meta http-equiv='refresh' content='2;url=/dashboard'>
</head>
<body>
    <div class='container'>
        <div class='card slide-up'>
            <div class='card-body text-center'>
                <h2>✅ Connexion automatique réussie</h2>
                <p>Vous êtes maintenant connecté en tant qu'utilisateur test.</p>
                <div class='loading mb-3'></div>
                <p class='text-muted'>Redirection automatique vers le Dashboard...</p>
                <a href='/dashboard' class='btn btn-primary mt-3'>Continuer manuellement</a>
            </div>
        </div>
    </div>
</body>
</html>";
        break;
        
    // Page de démo finale
    case '/temp':
        include 'temp.html';
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>Chemin demandé: $path</p>";
        echo "<a href='" . $base . "'>Accueil</a>";
        break;
}
?>