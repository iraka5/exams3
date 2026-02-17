<?php
// routes.php
require_once __DIR__ . '/config/config.php';

define('BASE_URL', '/exams3-main/exams3');

// Récupérer l'URL
$request = $_SERVER['REQUEST_URI'];
if (strpos($request, BASE_URL) === 0) {
    $path = substr($request, strlen(BASE_URL));
} else {
    $path = $request;
}
$path = strtok($path, '?');
if ($path === '') $path = '/';

// Connexion BDD
$db = getDB();

// ROUTAGE
switch ($path) {
    
    // ========== PAGE D'ACCUEIL = CREATE.PHP ==========
    case '/':
    case '':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/create.php';
        break;
    
    // ========== TABLEAU DE BORD ==========
    // TABLEAU DE BORD
case '/tableau-bord':
    $stats = [
        'regions' => $db->query("SELECT COUNT(*) FROM regions")->fetchColumn(),
        'villes' => $db->query("SELECT COUNT(*) FROM ville")->fetchColumn(),
        'besoins' => $db->query("SELECT COUNT(*) FROM besoins")->fetchColumn(),
        'dons' => $db->query("SELECT COUNT(*) FROM dons")->fetchColumn()
    ];
    $dernieres_regions = $db->query("SELECT * FROM regions ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    $dernieres_villes = $db->query("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id ORDER BY v.id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    
    // CORRECTION: tableau_bord_simple.php au lieu de tableau_bord.php
    include __DIR__ . '/views/tableau_bord_simple.php';
    break;
    
    // ========== RÉGIONS ==========
    case '/regions':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/regions/index.php';
        break;
        
    case '/regions/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            if (!empty($nom)) {
                $stmt = $db->prepare("INSERT INTO regions (nom) VALUES (?)");
                $stmt->execute([$nom]);
            }
            header('Location: ' . BASE_URL . '/regions');
            exit;
        }
        break;
        
    case (preg_match('/^\/regions\/(\d+)$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $region = $db->prepare("SELECT * FROM regions WHERE id = ?");
        $region->execute([$id]);
        $region = $region->fetch(PDO::FETCH_ASSOC);
        // Ici vous afficheriez la vue show.php si elle existe
        header('Location: ' . BASE_URL . '/regions');
        exit;
        break;
        
    case (preg_match('/^\/regions\/(\d+)\/edit$/', $path, $matches) ? true : false):
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            if (!empty($nom)) {
                $stmt = $db->prepare("UPDATE regions SET nom = ? WHERE id = ?");
                $stmt->execute([$nom, $id]);
            }
            header('Location: ' . BASE_URL . '/regions');
            exit;
        } else {
            $region = $db->prepare("SELECT * FROM regions WHERE id = ?");
            $region->execute([$id]);
            $region = $region->fetch(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/regions/edit.php';
        }
        break;
        
    case (preg_match('/^\/regions\/(\d+)\/delete$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $db->prepare("DELETE FROM regions WHERE id = ?")->execute([$id]);
        header('Location: ' . BASE_URL . '/regions');
        exit;
        break;
    
    // ========== VILLES ==========
    case '/villes':
        $region_id = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;
        
        if ($region_id > 0) {
            $stmt = $db->prepare("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id WHERE v.id_regions = ? ORDER BY v.nom");
            $stmt->execute([$region_id]);
            $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $region = $db->prepare("SELECT * FROM regions WHERE id = ?");
            $region->execute([$region_id]);
            $region_selected = $region->fetch(PDO::FETCH_ASSOC);
        } else {
            $villes = $db->query("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id ORDER BY r.nom, v.nom")->fetchAll(PDO::FETCH_ASSOC);
            $region_selected = null;
        }
        
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/villes/index.php';
        break;
        
    case '/villes/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $id_regions = intval($_POST['id_regions'] ?? 0);
            if (!empty($nom) && $id_regions > 0) {
                $stmt = $db->prepare("INSERT INTO ville (nom, id_regions) VALUES (?, ?)");
                $stmt->execute([$nom, $id_regions]);
            }
            header('Location: ' . BASE_URL . '/villes');
            exit;
        }
        break;
        
    case (preg_match('/^\/villes\/(\d+)$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $ville = $db->prepare("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id WHERE v.id = ?");
        $ville->execute([$id]);
        $ville = $ville->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/villes/show.php';
        break;
        
    case (preg_match('/^\/villes\/(\d+)\/edit$/', $path, $matches) ? true : false):
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $id_regions = intval($_POST['id_regions'] ?? 0);
            if (!empty($nom) && $id_regions > 0) {
                $stmt = $db->prepare("UPDATE ville SET nom = ?, id_regions = ? WHERE id = ?");
                $stmt->execute([$nom, $id_regions, $id]);
            }
            header('Location: ' . BASE_URL . '/villes');
            exit;
        } else {
            $ville = $db->prepare("SELECT * FROM ville WHERE id = ?");
            $ville->execute([$id]);
            $ville = $ville->fetch(PDO::FETCH_ASSOC);
            $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/villes/edit.php';
        }
        break;
        
    case (preg_match('/^\/villes\/(\d+)\/delete$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $db->prepare("DELETE FROM ville WHERE id = ?")->execute([$id]);
        header('Location: ' . BASE_URL . '/villes');
        exit;
        break;
    
    // ========== BESOINS ==========
    case '/besoins':
        $id_ville = isset($_GET['id_ville']) ? intval($_GET['id_ville']) : 0;
        
        if ($id_ville > 0) {
            $stmt = $db->prepare("SELECT b.*, v.nom as ville_nom FROM besoins b JOIN ville v ON b.id_ville = v.id WHERE b.id_ville = ? ORDER BY b.id DESC");
            $stmt->execute([$id_ville]);
            $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $besoins = $db->query("SELECT b.*, v.nom as ville_nom FROM besoins b JOIN ville v ON b.id_ville = v.id ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/besoins/index.php';
        break;
        
    case '/besoins/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $nombre = floatval($_POST['nombre'] ?? 0);
            $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);
            
            if (!empty($nom) && $nombre > 0 && $prix_unitaire > 0 && $id_ville > 0) {
                $stmt = $db->prepare("INSERT INTO besoins (nom, nombre, prix_unitaire, id_ville) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $nombre, $prix_unitaire, $id_ville]);
            }
            header('Location: ' . BASE_URL . '/besoins');
            exit;
        } else {
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/besoins/create.php';
        }
        break;
        
    case (preg_match('/^\/besoins\/(\d+)$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $besoin = $db->prepare("SELECT b.*, v.nom as ville_nom FROM besoins b JOIN ville v ON b.id_ville = v.id WHERE b.id = ?");
        $besoin->execute([$id]);
        $besoin = $besoin->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/besoins/show.php';
        break;
        
    case (preg_match('/^\/besoins\/(\d+)\/edit$/', $path, $matches) ? true : false):
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $nombre = floatval($_POST['nombre'] ?? 0);
            $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);
            
            if (!empty($nom) && $nombre > 0 && $prix_unitaire > 0 && $id_ville > 0) {
                $stmt = $db->prepare("UPDATE besoins SET nom = ?, nombre = ?, prix_unitaire = ?, id_ville = ? WHERE id = ?");
                $stmt->execute([$nom, $nombre, $prix_unitaire, $id_ville, $id]);
            }
            header('Location: ' . BASE_URL . '/besoins');
            exit;
        } else {
            $besoin = $db->prepare("SELECT * FROM besoins WHERE id = ?");
            $besoin->execute([$id]);
            $besoin = $besoin->fetch(PDO::FETCH_ASSOC);
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/besoins/edit.php';
        }
        break;
        
    case (preg_match('/^\/besoins\/(\d+)\/delete$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $db->prepare("DELETE FROM besoins WHERE id = ?")->execute([$id]);
        header('Location: ' . BASE_URL . '/besoins');
        exit;
        break;
    
    // ========== DONS ==========
    case '/dons':
        $dons = $db->query("SELECT d.*, v.nom as ville_nom FROM dons d JOIN ville v ON d.id_ville = v.id ORDER BY d.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/dons/index.php';
        break;
        
    case '/dons/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_donneur = trim($_POST['nom_donneur'] ?? '');
            $type_don = trim($_POST['type_don'] ?? '');
            $nombre_don = floatval($_POST['nombre_don'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);
            
            if (!empty($nom_donneur) && !empty($type_don) && $nombre_don > 0 && $id_ville > 0) {
                $stmt = $db->prepare("INSERT INTO dons (nom_donneur, type_don, nombre_don, id_ville) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
            }
            header('Location: ' . BASE_URL . '/dons');
            exit;
        } else {
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/dons/create.php';
        }
        break;
        
    case (preg_match('/^\/dons\/(\d+)$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $don = $db->prepare("SELECT d.*, v.nom as ville_nom FROM dons d JOIN ville v ON d.id_ville = v.id WHERE d.id = ?");
        $don->execute([$id]);
        $don = $don->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/dons/show.php';
        break;
        
    case (preg_match('/^\/dons\/(\d+)\/edit$/', $path, $matches) ? true : false):
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_donneur = trim($_POST['nom_donneur'] ?? '');
            $type_don = trim($_POST['type_don'] ?? '');
            $nombre_don = floatval($_POST['nombre_don'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);
            
            if (!empty($nom_donneur) && !empty($type_don) && $nombre_don > 0 && $id_ville > 0) {
                $stmt = $db->prepare("UPDATE dons SET nom_donneur = ?, type_don = ?, nombre_don = ?, id_ville = ? WHERE id = ?");
                $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville, $id]);
            }
            header('Location: ' . BASE_URL . '/dons');
            exit;
        } else {
            $don = $db->prepare("SELECT * FROM dons WHERE id = ?");
            $don->execute([$id]);
            $don = $don->fetch(PDO::FETCH_ASSOC);
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/dons/edit.php';
        }
        break;
        
    case (preg_match('/^\/dons\/(\d+)\/delete$/', $path, $matches) ? true : false):
        $id = $matches[1];
        $db->prepare("DELETE FROM dons WHERE id = ?")->execute([$id]);
        header('Location: ' . BASE_URL . '/dons');
        exit;
        break;
    
    // ========== DÉCONNEXION ==========
    case '/logout':
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
        break;
    
    // ========== NOUVELLES ROUTES V3 - SYSTÈME DE VENTE ==========
    
    // Route de réinitialisation des données (accessible à tous)
    case '/reset-data':
        require_once __DIR__ . '/controllers/ResetController.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ResetController::executeReset();
        } else {
            ResetController::showResetForm();
        }
        break;
    
    // Route de vente d'article
    case (preg_match('/^\/dons\/(\d+)\/vendre$/', $path, $matches) ? true : false):
        require_once __DIR__ . '/controllers/VenteController.php';
        $don_id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            VenteController::processSale($don_id);
        } else {
            VenteController::showSaleForm($don_id);
        }
        break;
        
    // API pour vérifier si un article est vendable
    case (preg_match('/^\/api\/dons\/(\d+)\/vendable$/', $path, $matches) ? true : false):
        require_once __DIR__ . '/controllers/VenteController.php';
        $don_id = $matches[1];
        VenteController::checkVendable($don_id);
        break;
    
    // Route de configuration du taux de diminution (accessible à tous)
    case '/config-taux':
        require_once __DIR__ . '/controllers/ConfigController.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ConfigController::updateTaux();
        } else {
            ConfigController::showConfigForm();
        }
        break;
        
    // API pour récupérer le taux actuel
    case '/api/config/taux':
        require_once __DIR__ . '/controllers/ConfigController.php';
        ConfigController::getTauxApi();
        break;
    
    // ========== PAGE 404 ==========
    default:
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page non trouvée</title>
            <style>
                body { font-family: Arial; background: #f6f8fb; text-align: center; padding: 50px; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; }
                h1 { color: #13265C; }
                a { color: #13265C; text-decoration: none; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>404 - Page non trouvée</h1>
                <p>Le chemin demandé <strong>$path</strong> n'existe pas.</p>
                <p><a href='" . BASE_URL . "'>← Retour à l'accueil</a></p>
            </div>
        </body>
        </html>";
        break;
}
?>