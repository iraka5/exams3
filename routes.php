<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Don.php'; // Ajout important pour les méthodes de vente

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