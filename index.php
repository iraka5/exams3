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
            
            error_log("DEBUG SIGNUP: nom=$nom, email=$email, password_length=" . strlen($password));
            
            if (empty($nom) || empty($email) || empty($password)) {
                error_log("DEBUG: Champs vides");
                header('Location: ' . $base . '/signup?error=missing_fields');
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("DEBUG: Email invalide");
                header('Location: ' . $base . '/signup?error=invalid_email');
                exit;
            }
            
            if (strlen($password) < 6) {
                error_log("DEBUG: Mot de passe trop court");
                header('Location: ' . $base . '/signup?error=password_too_short');
                exit;
            }
            
            try {
                $pdo = getDB();
                error_log("DEBUG: DB connected");
                
                // Vérifier si l'email existe
                $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetch()) {
                    error_log("DEBUG: Email déjà existant");
                    header('Location: ' . $base . '/signup?error=email_exists');
                    exit;
                }
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
                error_log("DEBUG: Tentative INSERT");
                $stmt->execute([$nom, $email, $hashedPassword]);
                error_log("DEBUG: INSERT réussi");
                
                header('Location: ' . $base . '/login?success=registered');
                exit;
                
            } catch (Exception $e) {
                error_log("DEBUG: Exception - " . $e->getMessage());
                echo "Erreur DB: " . $e->getMessage();
                die();
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
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard Utilisateur - BNGRC</title>
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <style>
                :root { 
                    --brand: #13265C; 
                    --muted: #6b7280; 
                    --bg: #f6f8fb; 
                    --success: #059669;
                    --warning: #d97706;
                    --info: #0891b2;
                }
                * { box-sizing: border-box; }
                body {
                    font-family: Inter, Segoe UI, Arial, sans-serif;
                    background: var(--bg);
                    margin: 0;
                    padding: 20px;
                    color: #374151;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                .header {
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 6px 30px rgba(19,38,92,0.12);
                    padding: 24px 28px;
                    margin-bottom: 24px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .header h1 {
                    margin: 0;
                    color: var(--brand);
                    font-size: 24px;
                    font-weight: 600;
                }
                .user-info {
                    color: var(--muted);
                    font-size: 14px;
                }
                .grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    gap: 24px;
                    margin-bottom: 24px;
                }
                .card {
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 6px 30px rgba(19,38,92,0.12);
                    padding: 24px;
                    transition: transform 0.2s, box-shadow 0.2s;
                }
                .card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 40px rgba(19,38,92,0.15);
                }
                .card-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 16px;
                }
                .card-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 16px;
                    font-size: 20px;
                }
                .card-icon.besoins { background: rgba(239,68,68,0.1); color: #dc2626; }
                .card-icon.dons { background: rgba(34,197,94,0.1); color: #16a34a; }
                .card-icon.stats { background: rgba(59,130,246,0.1); color: #2563eb; }
                .card-icon.logout { background: rgba(107,114,128,0.1); color: var(--muted); }
                
                .card-content h3 {
                    margin: 0 0 8px;
                    color: var(--brand);
                    font-size: 18px;
                    font-weight: 600;
                }
                .card-content p {
                    margin: 0;
                    color: var(--muted);
                    font-size: 14px;
                    line-height: 1.5;
                }
                .btn {
                    display: inline-flex;
                    align-items: center;
                    padding: 12px 20px;
                    background: var(--brand);
                    color: white;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 500;
                    font-size: 14px;
                    transition: background 0.2s;
                    margin-top: 16px;
                }
                .btn:hover {
                    background: #0f1d4a;
                }
                .btn.secondary {
                    background: #f3f4f6;
                    color: var(--muted);
                }
                .btn.secondary:hover {
                    background: #e5e7eb;
                }
                .quick-stats {
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 6px 30px rgba(19,38,92,0.12);
                    padding: 24px;
                }
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                    gap: 20px;
                }
                .stat-item {
                    text-align: center;
                    padding: 16px;
                    border-radius: 10px;
                    background: var(--bg);
                }
                .stat-number {
                    font-size: 28px;
                    font-weight: 700;
                    color: var(--brand);
                    margin-bottom: 4px;
                }
                .stat-label {
                    font-size: 13px;
                    color: var(--muted);
                    font-weight: 500;
                }
                @media (max-width: 768px) {
                    .header {
                        flex-direction: column;
                        text-align: center;
                        gap: 12px;
                    }
                    .grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <header class="header">
                    <div>
                        <h1>Dashboard Utilisateur</h1>
                        <div class="user-info">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur'); ?> !</div>
                    </div>
                    <a href="<?php echo $base; ?>/logout" class="btn secondary">Déconnexion</a>
                </header>

                <div class="grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon besoins">📋</div>
                            <div class="card-content">
                                <h3>Consulter les Besoins</h3>
                                <p>Voir tous les besoins identifiés dans les différentes régions et villes.</p>
                            </div>
                        </div>
                        <a href="<?php echo $base; ?>/user/besoins" class="btn">Voir les besoins</a>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon dons">💝</div>
                            <div class="card-content">
                                <h3>Faire un Don</h3>
                                <p>Contribuez en faisant un don pour soutenir les communautés dans le besoin.</p>
                            </div>
                        </div>
                        <a href="<?php echo $base; ?>/user/dons" class="btn">Faire un don</a>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon stats">📊</div>
                            <div class="card-content">
                                <h3>Statistiques</h3>
                                <p>Consultez les statistiques des villes et l'évolution des besoins.</p>
                            </div>
                        </div>
                        <a href="<?php echo $base; ?>/user/villes" class="btn">Voir les statistiques</a>
                    </div>
                </div>

                <div class="quick-stats">
                    <h3 style="margin: 0 0 20px; color: var(--brand); font-size: 20px;">Aperçu Rapide</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Besoins Actifs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">8</div>
                            <div class="stat-label">Dons Effectués</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4</div>
                            <div class="stat-label">Villes Couvertes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">95%</div>
                            <div class="stat-label">Taux de Réponse</div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
        
    // USER BESOINS
    case '/user/besoins':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        // Récupérer les villes et besoins
        try {
            $pdo = getDB();
            $villes_stmt = $pdo->query("SELECT id, nom FROM ville ORDER BY nom");
            $villes = $villes_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Filtrer par ville si sélectionné
            $sql = "SELECT b.*, v.nom as ville_nom, r.nom as region_nom 
                    FROM besoins b 
                    JOIN ville v ON b.id_ville = v.id 
                    JOIN regions r ON v.id_regions = r.id 
                    WHERE 1=1";
            
            if (isset($_GET['id_ville']) && $_GET['id_ville'] != 0 && $_GET['id_ville'] != '') {
                $sql .= " AND b.id_ville = ?";
                $besoins_stmt = $pdo->prepare($sql . " ORDER BY b.created_at DESC");
                $besoins_stmt->execute([$_GET['id_ville']]);
            } else {
                $besoins_stmt = $pdo->prepare($sql . " ORDER BY b.created_at DESC");
                $besoins_stmt->execute();
            }
            $besoins = $besoins_stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $villes = [];
            $besoins = [];
        }
        require_once __DIR__ . '/views/users/besoins.php';
        break;
        
    // USER DONS
    case '/user/dons':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        require_once __DIR__ . '/views/users/dons_form.php';
        break;
        
    // USER VILLES (STATISTIQUES)
    case '/user/villes':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $base . '/login');
            exit;
        }
        // Récupérer les données des villes
        try {
            $pdo = getDB();
            $sql = "SELECT 
                        v.id, v.nom, r.nom as region_nom,
                        COUNT(DISTINCT b.id) as nb_besoins,
                        COUNT(DISTINCT d.id) as nb_dons,
                        COALESCE(SUM(b.quantite), 0) as total_besoins,
                        COALESCE(SUM(d.quantite), 0) as total_dons
                    FROM ville v
                    LEFT JOIN regions r ON v.id_regions = r.id
                    LEFT JOIN besoins b ON b.id_ville = v.id
                    LEFT JOIN dons d ON d.id_ville = v.id
                    GROUP BY v.id
                    ORDER BY v.nom";
            $stmt = $pdo->query($sql);
            $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $villes = [];
        }
        require_once __DIR__ . '/views/users/villes_stats.php';
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