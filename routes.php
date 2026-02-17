<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Don.php'; // Pour les méthodes de vente

if (!defined('BASE_URL')) {
    define('BASE_URL', '/exams3-main/exams3');
}

// URL actuelle
$request = $_SERVER['REQUEST_URI'];
<<<<<<< HEAD
=======
$path = $request;
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299

if (strpos($request, BASE_URL) === 0) {
    $path = substr($request, strlen(BASE_URL));
}

$path = strtok($path, '?');
if ($path === '') $path = '/';

// Connexion BDD
$db = getDB();

switch ($path) {

    // =======================
    // ACCUEIL = CREATE.PHP
    // =======================
    case '/':
        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/views/create.php';
        break;

    // =======================
    // TABLEAU DE BORD
    // =======================
    case '/tableau-bord':
        $sql = "SELECT 
                    r.nom as region_nom,
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
            if ($row['besoin_quantite'] > 0) {
                $row['progression'] = min(100, round(($row['dons_quantite'] / $row['besoin_quantite']) * 100, 1));
            } else {
                $row['progression'] = 0;
            }
        }

        include __DIR__ . '/views/tableau_bord_simple.php';
        break;

    // =======================
    // CONFIGURATION TAUX DE VENTE
    // =======================
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

    // =======================
    // REGIONS
    // =======================
    case '/regions':
        $regions = $db->query("
            SELECT r.*,
            (SELECT COUNT(*) FROM ville v WHERE v.id_regions = r.id) AS villes_count
            FROM regions r
            ORDER BY r.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/views/regions/index.php';
        break;

    case '/regions/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            if ($nom !== '') {
                $stmt = $db->prepare("INSERT INTO regions(nom) VALUES(?)");
                $stmt->execute([$nom]);
            }
            header("Location: " . BASE_URL . "/regions");
            exit;
        } else {
            include __DIR__ . '/views/regions/create.php';
        }
        break;

    // =======================
    // VILLES
    // =======================
    case '/villes':
        $region_id = isset($_GET['region_id']) ? intval($_GET['region_id']) : 0;

        $sql = "SELECT v.*, r.nom as region_nom
                FROM ville v
                JOIN regions r ON v.id_regions = r.id";

        $params = [];

        if ($region_id > 0) {
            $sql .= " WHERE v.id_regions = ?";
            $params[] = $region_id;
        }

        $sql .= " ORDER BY r.nom, v.nom";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/views/villes/index.php';
        break;

    case '/villes/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $id_regions = intval($_POST['id_regions'] ?? 0);

            if ($nom !== '' && $id_regions > 0) {
                $stmt = $db->prepare("INSERT INTO ville(nom, id_regions) VALUES(?, ?)");
                $stmt->execute([$nom, $id_regions]);
            }

            header("Location: " . BASE_URL . "/villes");
            exit;
        } else {
            $regions = $db->query("SELECT * FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/villes/create.php';
        }
        break;

    // =======================
    // BESOINS
    // =======================
    case '/besoins':
        $id_ville = isset($_GET['id_ville']) ? intval($_GET['id_ville']) : 0;

        if ($id_ville > 0) {
            $stmt = $db->prepare("
                SELECT b.*, v.nom as ville_nom
                FROM besoins b
                JOIN ville v ON b.id_ville = v.id
                WHERE b.id_ville = ?
                ORDER BY b.id DESC
            ");
            $stmt->execute([$id_ville]);
            $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $besoins = $db->query("
                SELECT b.*, v.nom as ville_nom
                FROM besoins b
                JOIN ville v ON b.id_ville = v.id
                ORDER BY b.id DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/views/besoins/index.php';
        break;

    case '/besoins/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $nombre = floatval($_POST['nombre'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);

            if ($nom !== '' && $nombre > 0 && $id_ville > 0) {
                $stmt = $db->prepare("INSERT INTO besoins(nom, nombre, id_ville) VALUES(?, ?, ?)");
                $stmt->execute([$nom, $nombre, $id_ville]);
            }

            header("Location: " . BASE_URL . "/besoins");
            exit;
        } else {
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/besoins/create.php';
        }
        break;

    // =======================
    // DONS
    // =======================
    case '/dons':
        $dons = $db->query("
            SELECT d.*, v.nom as ville_nom
            FROM dons d
            JOIN ville v ON d.id_ville = v.id
            ORDER BY d.id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/views/dons/index.php';
        break;

    case '/dons/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_donneur = trim($_POST['nom_donneur'] ?? '');
            $type_don = trim($_POST['type_don'] ?? '');
            $nombre_don = floatval($_POST['nombre_don'] ?? 0);
            $id_ville = intval($_POST['id_ville'] ?? 0);

            if ($nom_donneur !== '' && $type_don !== '' && $nombre_don > 0 && $id_ville > 0) {
                $stmt = $db->prepare("INSERT INTO dons(nom_donneur, type_don, nombre_don, id_ville) VALUES(?, ?, ?, ?)");
                $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
            }

            header("Location: " . BASE_URL . "/dons");
            exit;
        } else {
            $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/dons/create.php';
        }
        break;

    // =======================
    // VENTES
    // =======================
    case '/ventes':
        $ventes = Don::getDernieresVentes(50);
        $stats = Don::getStatsVentes();
        include __DIR__ . '/views/ventes/index.php';
        break;

    // =======================
    // ACHATS
    // =======================
    case '/achats':
        // Récupérer les achats avec les infos associées
        $achats = $db->query("
            SELECT a.*, 
                   v.nom as ville_nom, 
                   r.nom as region_nom,
                   b.nom as besoin_nom,
                   b.type_besoin
            FROM achats a
            JOIN besoins b ON a.id_besoin = b.id
            JOIN ville v ON b.id_ville = v.id
            JOIN regions r ON v.id_regions = r.id
            ORDER BY a.date_achat DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les villes pour le filtre
        $villes = $db->query("
            SELECT v.*, r.nom as region_nom 
            FROM ville v 
            JOIN regions r ON v.id_regions = r.id 
            ORDER BY r.nom, v.nom
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/views/achats/index.php';
        break;

    case '/achats/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_besoin = intval($_POST['id_besoin'] ?? 0);
            $quantite = floatval($_POST['quantite'] ?? 0);
            $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
            
            if ($id_besoin > 0 && $quantite > 0 && $prix_unitaire > 0) {
                $montant = $quantite * $prix_unitaire;
                $stmt = $db->prepare("
                    INSERT INTO achats (id_besoin, quantite, prix_unitaire, montant, date_achat) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$id_besoin, $quantite, $prix_unitaire, $montant]);
                header("Location: " . BASE_URL . "/achats?success=created");
                exit;
            }
            header("Location: " . BASE_URL . "/achats/create?error=invalid");
            exit;
        } else {
            // Récupérer les besoins pour le formulaire
            $besoins = $db->query("
                SELECT b.*, v.nom as ville_nom 
                FROM besoins b 
                JOIN ville v ON b.id_ville = v.id 
                ORDER BY v.nom, b.nom
            ")->fetchAll(PDO::FETCH_ASSOC);
            include __DIR__ . '/views/achats/create.php';
        }
        break;

    case '/achats/recapitulatif':
        // Calculer les totaux
        $totaux = [];
        
        // Total des besoins
        $stmt = $db->query("SELECT COALESCE(SUM(nombre * prix_unitaire), 0) as total FROM besoins");
        $totaux['besoins_total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total des achats
        $stmt = $db->query("SELECT COALESCE(SUM(montant), 0) as total FROM achats");
        $totaux['besoins_satisfaits'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total des dons
        $stmt = $db->query("SELECT COALESCE(SUM(nombre_don), 0) as total FROM dons");
        $totaux['dons_recus'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Fonds restants
        $totaux['fonds_restants'] = $totaux['dons_recus'] - $totaux['besoins_satisfaits'];
        
        // Achats par ville
        $achats_par_ville = $db->query("
            SELECT v.nom as ville_nom, r.nom as region_nom,
                   COUNT(a.id) as nb_achats,
                   COALESCE(SUM(a.montant), 0) as montant_achats
            FROM achats a
            JOIN besoins b ON a.id_besoin = b.id
            JOIN ville v ON b.id_ville = v.id
            JOIN regions r ON v.id_regions = r.id
            GROUP BY v.id
            ORDER BY r.nom, v.nom
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/views/achats/recapitulatif.php';
        break;

    // =======================
    // VENDRE UN DON
    // =======================
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

    // =======================
    // API POUR VÉRIFICATION VENDABLE
    // =======================
    case (preg_match('/^\/api\/dons\/(\d+)\/vendable$/', $path, $matches) ? true : false):
        $id_don = $matches[1];
        header('Content-Type: application/json');
        echo json_encode(Don::isVendable($id_don));
        exit;

    // =======================
    // DELETE (REGIONS/VILLES/BESOINS/DONS)
    // =======================
    case (preg_match('/^\/(regions|villes|besoins|dons)\/(\d+)\/delete$/', $path, $m) ? true : false):
        $table = $m[1];
        $id = intval($m[2]);

        $map = [
            'regions' => 'regions',
            'villes' => 'ville',
            'besoins' => 'besoins',
            'dons' => 'dons'
        ];

        $db_table = $map[$table];

        $stmt = $db->prepare("DELETE FROM $db_table WHERE id=?");
        $stmt->execute([$id]);

        header("Location: " . BASE_URL . "/$table");
        exit;

    // =======================
    // RESET
    // =======================
    case '/reset-data':
        include __DIR__ . '/views/reset_form.php';
        break;

    // =======================
    // 404
    // =======================
    default:
        http_response_code(404);
<<<<<<< HEAD
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page non trouvée</title>
            <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
        </head>
        <body>
            <div class="container">
                <h1>404 - Page non trouvée</h1>
                <p>Le chemin demandé <strong><?= htmlspecialchars($path) ?></strong> n'existe pas.</p>
                <p><a href="<?= BASE_URL ?>">← Retour à l'accueil</a></p>
            </div>
        </body>
        </html>
        <?php
=======
        echo "<h1>404</h1><p>Le chemin demandé <b>$path</b> n'existe pas.</p>";
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299
        break;
}
?>