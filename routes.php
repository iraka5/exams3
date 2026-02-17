<?php
// routes.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';

// Vérifier si BASE_URL est déjà défini
if (!defined('BASE_URL')) {
    define('BASE_URL', '/exams3-main/exams3');
}

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
    case '/tableau-bord':
        // Récupérer toutes les données aggrégées
        $sql = "SELECT 
                    r.id as region_id,
                    r.nom as region_nom,
                    v.id as ville_id,
                    v.nom as ville_nom,
                    b.nom as type_besoin,
                    COALESCE(b.nombre, 0) as besoin_quantite,
                    COALESCE(SUM(d.nombre_don), 0) as dons_quantite
                FROM regions r
                LEFT JOIN ville v ON r.id = v.id_regions
                LEFT JOIN besoins b ON v.id = b.id_ville
                LEFT JOIN dons d ON v.id = d.id_ville
                GROUP BY v.id, b.id
                ORDER BY r.nom, v.nom";
        
        $donnees = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialiser les valeurs et calculer la progression pour chaque ligne
        foreach ($donnees as &$row) {
            $row['besoin_quantite'] = $row['besoin_quantite'] ?? 0;
            $row['dons_quantite'] = $row['dons_quantite'] ?? 0;
            $row['type_besoin'] = $row['type_besoin'] ?? 'Non spécifié';
            $row['region_nom'] = $row['region_nom'] ?? 'Non définie';
            $row['ville_nom'] = $row['ville_nom'] ?? 'Non définie';
            
            if ($row['besoin_quantite'] > 0) {
                $row['progression'] = min(100, round(($row['dons_quantite'] / $row['besoin_quantite']) * 100, 1));
            } else {
                $row['progression'] = 0;
            }
        }
        
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

        // ========== PAGE DE CRÉATION ==========
case '/create':
    $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    include __DIR__ . '/views/create.php';
    break;
    
// ========== VILLES ==========
case '/villes':
    // Récupérer toutes les régions pour le filtre
    $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer l'ID de la région depuis l'URL
    $region_id = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;
    
    // Initialiser la variable region_selected
    $region_selected = null;
    
    // Construire la requête en fonction du filtre
    if ($region_id > 0) {
        // Vérifier que la région existe
        $region_check = $db->prepare("SELECT * FROM regions WHERE id = ?");
        $region_check->execute([$region_id]);
        $region_selected = $region_check->fetch(PDO::FETCH_ASSOC);
        
        if ($region_selected) {
            // Récupérer les villes de la région sélectionnée
            $stmt = $db->prepare("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id WHERE v.id_regions = ? ORDER BY v.nom");
            $stmt->execute([$region_id]);
            $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si la région n'existe pas, on ignore le filtre
            $villes = $db->query("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id ORDER BY r.nom, v.nom")->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        // Pas de filtre, toutes les villes
        $villes = $db->query("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id ORDER BY r.nom, v.nom")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    include __DIR__ . '/views/villes/index.php';
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
    
    // ========== SUPPRESSION ==========
    case (preg_match('/^\/(regions|villes|besoins|dons)\/(\d+)\/delete$/', $path, $matches) ? true : false):
        $table = $matches[1];
        $id = $matches[2];
        
        $table_map = [
            'regions' => 'regions',
            'villes' => 'ville',
            'besoins' => 'besoins',
            'dons' => 'dons'
        ];
        
        $db_table = $table_map[$table];
        $stmt = $db->prepare("DELETE FROM $db_table WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: ' . BASE_URL . '/' . $table);
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