<?php
// routes.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Don.php';  // IMPORTANT: Inclusion du modèle Don

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
    
    // ========== 2. TABLEAU DE BORD ==========
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
    
    // ========== 3. TOUTES LES ROUTES DE CRÉATION ==========
    
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
    
    // DONS - Création
    case '/dons/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
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
            // AFFICHAGE DU FORMULAIRE
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/dons/create.php';
        }
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
    
    // VENTES - Liste
    case '/ventes':
        $ventes = Don::getDernieresVentes(50);
        $stats = Don::getStatsVentes();
        include __DIR__ . '/views/ventes/index.php';
        break;
    
    // ACHATS - Liste
    case '/achats':
        $villes = $db->query("SELECT v.*, r.nom as region_nom FROM ville v JOIN regions r ON v.id_regions = r.id ORDER BY r.nom, v.nom")->fetchAll(PDO::FETCH_ASSOC);
        $achats = $db->query("
            SELECT a.*, v.nom as ville_nom, r.nom as region_nom, b.nom as besoin_nom, b.type_besoin 
            FROM achats a
            JOIN besoins b ON a.id_besoin = b.id
            JOIN ville v ON b.id_ville = v.id
            JOIN regions r ON v.id_regions = r.id
            ORDER BY a.date_achat DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/achats/index.php';
        break;
    
    // ACHATS - Création
    case '/achats/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TRAITEMENT DU FORMULAIRE
            $id_besoin = intval($_POST['id_besoin'] ?? 0);
            $quantite = floatval($_POST['quantite'] ?? 0);
            $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
            
            if ($id_besoin > 0 && $quantite > 0 && $prix_unitaire > 0) {
                $montant = $quantite * $prix_unitaire;
                $stmt = $db->prepare("INSERT INTO achats (id_besoin, quantite, prix_unitaire, montant, date_achat) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$id_besoin, $quantite, $prix_unitaire, $montant]);
                header('Location: ' . BASE_URL . '/achats?success=created');
                exit;
            }
            header('Location: ' . BASE_URL . '/achats/create?error=invalid');
            exit;
        } else {
            // AFFICHAGE DU FORMULAIRE
            $besoins = $db->query("SELECT b.*, v.nom as ville_nom FROM besoins b JOIN ville v ON b.id_ville = v.id ORDER BY v.nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/achats/create.php';
        }
        break;
    
    // ACHATS - Récapitulatif
    case '/achats/recapitulatif':
        $stats_achats = $db->query("SELECT COUNT(*) as total, SUM(montant) as total_montant FROM achats")->fetch(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/achats/recapitulatif.php';
        break;
    
    // ========== 5. PARAMÈTRES ==========
    case '/config-taux':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement du formulaire
            $taux = floatval($_POST['taux_diminution'] ?? 10);
            
            if ($taux < 0 || $taux > 100) {
                header('Location: ' . BASE_URL . '/config-taux?error=taux_invalide');
                exit;
            }
            
            try {
                // Récupérer l'ancien taux
                $stmt = $db->prepare("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
                $stmt->execute();
                $ancien = $stmt->fetch(PDO::FETCH_ASSOC);
                $ancien_taux = $ancien ? $ancien['valeur'] : 10;
                
                // Mettre à jour le taux
                $stmt = $db->prepare("UPDATE parametres SET valeur = ? WHERE cle = 'taux_diminution_vente'");
                $stmt->execute([$taux]);
                
                header('Location: ' . BASE_URL . '/config-taux?success=1&ancien=' . $ancien_taux . '&nouveau=' . $taux);
                exit;
            } catch (Exception $e) {
                header('Location: ' . BASE_URL . '/config-taux?error=erreur_sauvegarde');
                exit;
            }
        } else {
            // Afficher la page de configuration
            $parametres = $db->query("SELECT * FROM parametres")->fetchAll(PDO::FETCH_ASSOC);
            $stats_vente = Don::getStatsVentes();
            $dernieres_ventes = Don::getDernieresVentes(10);
            include __DIR__ . '/views/config-taux.php';
        }
        break;
    
    // ========== 6. VENDRE UN DON ==========
    case '/dons/vendre':
        $id_don = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement de la vente
            $id_don = intval($_POST['id_don'] ?? 0);
            
            if ($id_don <= 0) {
                header('Location: ' . BASE_URL . '/dons');
                exit;
            }
            
            // Vérifier si le don peut être vendu
            $check = Don::isVendable($id_don);
            if (!$check['vendable']) {
                header('Location: ' . BASE_URL . '/dons/vendre?id=' . $id_don . '&error=vente_non_permise&raison=' . urlencode($check['raison']));
                exit;
            }
            
            // Récupérer le taux actuel
            $stmt = $db->query("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
            $taux = $stmt->fetch(PDO::FETCH_ASSOC);
            $taux_diminution = $taux ? floatval($taux['valeur']) : 10;
            
            // Vendre le don
            if (Don::vendre($id_don, $taux_diminution)) {
                header('Location: ' . BASE_URL . '/ventes?success=vendu');
            } else {
                header('Location: ' . BASE_URL . '/dons/vendre?id=' . $id_don . '&error=erreur_vente');
            }
            exit;
            
        } else {
            // Afficher la page de confirmation de vente
            if ($id_don <= 0) {
                header('Location: ' . BASE_URL . '/dons');
                exit;
            }
            
            $don = Don::find($id_don);
            if (!$don) {
                header('Location: ' . BASE_URL . '/dons');
                exit;
            }
            
            // Vérifier si vendable
            $check = Don::isVendable($id_don);
            
            // Calculer le prix
            $stmt = $db->query("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
            $taux = $stmt->fetch(PDO::FETCH_ASSOC);
            $taux_diminution = $taux ? floatval($taux['valeur']) : 10;
            
            $prix_info = Don::calculPrixReduit($id_don, $taux_diminution);
            $prix_original = $prix_info['prix_original'] ?? 0;
            $prix_vente = $prix_info['prix_reduit'] ?? 0;
            
            // Variables pour la vue
            $error = $_GET['error'] ?? '';
            $raison = $_GET['raison'] ?? '';
            
            include __DIR__ . '/views/dons/vendre.php';
        }
        break;
    
    // ========== 7. API ==========
    case (preg_match('/^\/api\/dons\/(\d+)\/vendable$/', $path, $matches) ? true : false):
        $id_don = $matches[1];
        header('Content-Type: application/json');
        echo json_encode(Don::isVendable($id_don));
        exit;
        break;
    
    // ========== 8. SUPPRESSION ==========
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
    
    // ========== 9. RÉINITIALISATION ==========
    case '/reset-data':
        include __DIR__ . '/views/reset-data.php';
        break;
    
    // ========== 10. DÉCONNEXION ==========
    case '/logout':
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
        break;
    
    // ========== 11. PAGE 404 ==========
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