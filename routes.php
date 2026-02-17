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
    
    // ========== 1. PAGE D'ACCUEIL = CREATE.PHP ==========
    case '/':
    case '':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/create.php';
        break;
    
    // ========== 2. TOUTES LES ROUTES DE CRÉATION ==========
    
    // RÉGIONS - Création
    case '/regions/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
            $nom = trim($_POST['nom'] ?? '');
            if (!empty($nom)) {
                $stmt = $db->prepare("INSERT INTO regions (nom) VALUES (?)");
                $stmt->execute([$nom]);
            }
            header('Location: ' . BASE_URL . '/regions');
            exit;
        } else {
            // AFFICHAGE DU FORMULAIRE
            include __DIR__ . '/views/regions/create.php';
        }
        break;
    
    // VILLES - Création
    case '/villes/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
            $nom = trim($_POST['nom'] ?? '');
            $id_regions = intval($_POST['id_regions'] ?? 0);
            if (!empty($nom) && $id_regions > 0) {
                $stmt = $db->prepare("INSERT INTO ville (nom, id_regions) VALUES (?, ?)");
                $stmt->execute([$nom, $id_regions]);
            }
            header('Location: ' . BASE_URL . '/villes');
            exit;
        } else {
            // AFFICHAGE DU FORMULAIRE
            $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/villes/create.php';
        }
        break;
    
    // BESOINS - Création
    case '/besoins/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
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
            // AFFICHAGE DU FORMULAIRE
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/besoins/create.php';
        }
        break;
    
    // DONS - Création (AVEC VÉRIFICATION)
    case '/dons/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
            $nom_donneur = trim($_POST['nom_donneur'] ?? '');
            $type_don = trim($_POST['type_don'] ?? '');
            $nombre_don = floatval($_POST['nombre_don'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);
            
            if (!empty($nom_donneur) && !empty($type_don) && $nombre_don > 0 && $id_ville > 0) {
                
                // ===== VÉRIFICATION DE LA QUANTITÉ =====
                // Calculer le total des besoins pour cette ville
                $stmt = $db->prepare("SELECT COALESCE(SUM(nombre), 0) as total_besoins FROM besoins WHERE id_ville = ?");
                $stmt->execute([$id_ville]);
                $total_besoins = $stmt->fetch(PDO::FETCH_ASSOC)['total_besoins'];
                
                // Calculer le total des dons déjà reçus pour cette ville
                $stmt = $db->prepare("SELECT COALESCE(SUM(nombre_don), 0) as total_dons FROM dons WHERE id_ville = ?");
                $stmt->execute([$id_ville]);
                $total_dons = $stmt->fetch(PDO::FETCH_ASSOC)['total_dons'];
                
                // Vérifier si le nouveau don dépasse les besoins
                $nouveau_total = $total_dons + $nombre_don;
                $max_autorise = $total_besoins - $total_dons;
                
                if ($max_autorise <= 0) {
                    // Plus aucun besoin à satisfaire
                    header('Location: ' . BASE_URL . '/dons/create?error=plus_besoin');
                    exit;
                }
                
                if ($nouveau_total > $total_besoins) {
                    // Rediriger avec un message d'erreur
                    header('Location: ' . BASE_URL . '/dons/create?error=depassement&max=' . $max_autorise);
                    exit;
                }
                // ===== FIN DE LA VÉRIFICATION =====
                
                // Si tout est OK, insérer le don
                $stmt = $db->prepare("INSERT INTO dons (nom_donneur, type_don, nombre_don, id_ville) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
                
                header('Location: ' . BASE_URL . '/dons?success=created');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/dons/create?error=invalid');
                exit;
            }
        } else {
            // AFFICHAGE DU FORMULAIRE
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/dons/create.php';
        }
        break;
    
    // ========== 3. TABLEAU DE BORD ==========
    case '/tableau-bord':
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
    
    // ========== 4. ROUTES DE LISTAGE ==========
    
    // RÉGIONS - Liste
    case '/regions':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/regions/index.php';
        break;
    
    // VILLES - Liste
    case '/villes':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $region_id = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;
        $region_selected = null;
        
        $sql = "SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id";
        $params = [];
        
        if ($region_id > 0) {
            $sql .= " WHERE v.id_regions = ?";
            $params[] = $region_id;
            $region = $db->prepare("SELECT * FROM regions WHERE id = ?");
            $region->execute([$region_id]);
            $region_selected = $region->fetch(PDO::FETCH_ASSOC);
        }
        
        $sql .= " ORDER BY r.nom, v.nom";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/views/villes/index.php';
        break;
    
    // BESOINS - Liste
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
    
    // DONS - Liste
    case '/dons':
        $dons = $db->query("SELECT d.*, v.nom as ville_nom FROM dons d JOIN ville v ON d.id_ville = v.id ORDER BY d.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/dons/index.php';
        break;
    
    // ========== 5. SUPPRESSION ==========
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
    
    // ========== 6. DÉCONNEXION ==========
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