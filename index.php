<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/RegionController.php';
require_once __DIR__ . '/controllers/VilleController.php';
require_once __DIR__ . '/controllers/BesoinController.php';
require_once __DIR__ . '/controllers/DonController.php';
require_once __DIR__ . '/controllers/AchatController.php';

// Configuration des vues pour FlightPHP  
Flight::set('flight.views.path', __DIR__ . '/views');

// Fonction helper pour vérifier si admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction helper pour vérifier authentification
function isAuthenticated() {
    return isset($_SESSION['user']);
}

// Fonction helper pour vérifier authentification utilisateur
function isUser() {
    return isset($_SESSION['user']) && $_SESSION['role'] === 'user';
}

// Fonction helper pour forcer l'authentification utilisateur
function requireUserAuth() {
    if (!isUser()) {
        Flight::redirect('/exams3-main/exams3/user/login');
        return false;
    }
    return true;
}

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
    if (!isAuthenticated()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    
    // Récupération des données des achats par ville
    try {
        require_once __DIR__ . '/models/Achat.php';
        $achats_par_ville = Achat::getMontantAchatsByVille();
        $totaux_globaux = Achat::calculerTotauxGlobaux();
        
        Flight::render('tableau_bord_simple', [
            'achats_par_ville' => $achats_par_ville,
            'totaux' => $totaux_globaux
        ]);
    } catch (Exception $e) {
        // En cas d'erreur, fournir des valeurs par défaut
        Flight::render('tableau_bord_simple', [
            'achats_par_ville' => [],
            'totaux' => [
                'besoins_total' => 0,
                'besoins_satisfaits' => 0,
                'dons_recus' => 0,
                'dons_dispatches' => 0,
                'fonds_restants' => 0,
                'taux_satisfaction' => 0
            ],
            'error' => 'Erreur lors du chargement des données: ' . $e->getMessage()
        ]);
    }
});

/* ROUTES LOGIN */
Flight::route('GET /login', function(){
    include 'views/login.html';
});

// Compatibilité ancien lien direct /login.html
Flight::route('GET /login.html', function(){
    Flight::redirect('/login');
});

Flight::route('POST /login', function(){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (LoginController::authenticate($email, $password)) {
        if ($_SESSION['role'] === 'admin') {
            Flight::redirect('/create'); // admin → créer ressources
        } else {
            Flight::redirect('/dons'); // user → page dons
        }
    } else {
        echo "Identifiants incorrects";
    }
});

/* ROUTE TABLEAU DE BORD */
Flight::route('GET /tableau-bord', function(){
    if (!isAdmin()) {
        Flight::redirect('/login');
        return;
    }
    
    // Récupération des données des achats par ville
    try {
        require_once __DIR__ . '/models/Achat.php';
        $achats_par_ville = Achat::getMontantAchatsByVille();
        $totaux_globaux = Achat::calculerTotauxGlobaux();
        
        Flight::render('tableau_bord_simple', [
            'achats_par_ville' => $achats_par_ville,
            'totaux' => $totaux_globaux
        ]);
    } catch (Exception $e) {
        // En cas d'erreur, fournir des valeurs par défaut
        Flight::render('tableau_bord_simple', [
            'achats_par_ville' => [],
            'totaux' => [
                'besoins_total' => 0,
                'besoins_satisfaits' => 0,
                'dons_recus' => 0,
                'dons_dispatches' => 0,
                'fonds_restants' => 0,
                'taux_satisfaction' => 0
            ],
            'error' => 'Erreur lors du chargement des données: ' . $e->getMessage()
        ]);
    }
});

/* ROUTE CREATION */
Flight::route('GET /create', function(){
    if (!isAdmin()) {
        Flight::redirect('/login');
        return;
    }
    // Load regions and villes to populate selects in the create view
    try {
        $db = getDB();
        $stmt = $db->query("SELECT id, nom FROM regions ORDER BY nom");
        $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->query("SELECT id, nom FROM ville ORDER BY nom");
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $regions = [];
        $villes = [];
    }

    Flight::render('create', ['regions' => $regions, 'villes' => $villes]);
});

/* ROUTE LOGOUT */
Flight::route('GET /logout', function(){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();   // supprime toutes les variables de session
    session_destroy(); // détruit la session
    Flight::redirect('/login'); // redirige vers la page de connexion
});

/* ROUTES UTILISATEURS */
Flight::route('GET /user/register', ['UserController', 'registerForm']);
Flight::route('POST /user/register', ['UserController', 'register']);
Flight::route('GET /user/login', ['UserController', 'loginForm']);
Flight::route('POST /user/login', ['UserController', 'authenticate']);
Flight::route('GET /user/logout', ['UserController', 'logout']);
Flight::route('GET /user/dashboard', ['UserController', 'dashboard']);

// Routes utilisateur pour consulter les besoins (lecture seule)
Flight::route('GET /user/besoins', function(){
    if (!requireUserAuth()) return;
    $db = getDB();
    $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
    $id_ville = isset($_GET["id_ville"]) ? intval($_GET["id_ville"]) : 0;
    
    if ($id_ville > 0) {
        $besoins = $db->prepare("SELECT besoins.*, ville.nom AS ville_nom FROM besoins JOIN ville ON besoins.id_ville = ville.id WHERE besoins.id_ville = ? ORDER BY besoins.id DESC");
        $besoins->execute([$id_ville]);
        $besoins = $besoins->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $besoins = $db->query("SELECT besoins.*, ville.nom AS ville_nom FROM besoins JOIN ville ON besoins.id_ville = ville.id ORDER BY besoins.id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    include __DIR__ . '/views/users/besoins.php';
});

// Route utilisateur pour faire des dons
Flight::route('GET /user/dons', function(){
    if (!requireUserAuth()) return;
    $db = getDB();
    $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
    include __DIR__ . '/views/users/dons_form.php';
});

Flight::route('POST /user/dons', function(){
    if (!requireUserAuth()) return;
    
    $nom_donneur = trim($_POST["nom_donneur"] ?? "");
    $type_don = trim($_POST["type_don"] ?? "");
    $nombre_don = floatval($_POST["nombre_don"] ?? 0);
    $id_ville = intval($_POST["id_ville"] ?? 0);

    if ($nom_donneur === "" || $type_don === "" || $nombre_don <= 0 || $id_ville <= 0) {
        Flight::redirect("/exams3-main/exams3/user/dons?error=1");
        return;
    }

    $db = getDB();
    $sql = "INSERT INTO dons(nom_donneur, type_don, nombre_don, id_ville) VALUES(?,?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
    
    Flight::redirect("/exams3-main/exams3/user/dashboard?success=don");
});

// Route utilisateur pour voir les tableaux par ville
Flight::route('GET /user/villes', function(){
    if (!requireUserAuth()) return;
    $db = getDB();
    
    // Statistiques par ville
    $sql = "SELECT 
                v.id, v.nom as ville_nom, r.nom as region_nom,
                COUNT(DISTINCT b.id) as nb_besoins,
                COUNT(DISTINCT d.id) as nb_dons,
                COALESCE(SUM(b.nombre), 0) as total_besoins,
                COALESCE(SUM(d.nombre_don), 0) as total_dons
            FROM ville v 
            JOIN regions r ON v.id_regions = r.id
            LEFT JOIN besoins b ON v.id = b.id_ville
            LEFT JOIN dons d ON v.id = d.id_ville
            GROUP BY v.id, v.nom, r.nom
            ORDER BY r.nom, v.nom";
    
    $villes = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    include __DIR__ . '/views/users/villes_stats.php';
});




/* ROUTES REGIONS */
Flight::route('GET /regions', ['RegionController', 'index']);
Flight::route('GET /regions/create', ['RegionController', 'createForm']);
Flight::route('POST /regions', ['RegionController', 'store']);
Flight::route('GET /regions/@id', ['RegionController', 'show']);
Flight::route('GET /regions/@id/edit', ['RegionController', 'editForm']);
Flight::route('POST /regions/@id', ['RegionController', 'update']);
Flight::route('GET /regions/@id/delete', ['RegionController', 'delete']);

/* ROUTES VILLES */
Flight::route('GET /villes', ['VilleController', 'index']);
Flight::route('GET /villes/create', ['VilleController', 'createForm']);
Flight::route('POST /villes', ['VilleController', 'store']);
Flight::route('GET /villes/@id', ['VilleController', 'show']);
Flight::route('GET /villes/@id/edit', ['VilleController', 'editForm']);
Flight::route('POST /villes/@id', ['VilleController', 'update']);
Flight::route('GET /villes/@id/delete', ['VilleController', 'delete']);

/* ROUTES BESOINS */
Flight::route('GET /besoins', ['BesoinController', 'index']);
Flight::route('GET /besoins/create', ['BesoinController', 'createForm']);
Flight::route('POST /besoins', ['BesoinController', 'store']);
Flight::route('GET /besoins/@id', ['BesoinController', 'show']);
Flight::route('GET /besoins/@id/edit', ['BesoinController', 'editForm']);
Flight::route('POST /besoins/@id', ['BesoinController', 'update']);
Flight::route('GET /besoins/@id/delete', ['BesoinController', 'delete']);

/* ROUTES DONS */
Flight::route('GET /dons', ['DonController', 'index']);
Flight::route('GET /dons/create', ['DonController', 'createForm']);
Flight::route('POST /dons', ['DonController', 'store']);
Flight::route('GET /dons/@id', ['DonController', 'show']);
Flight::route('GET /dons/@id/edit', ['DonController', 'editForm']);
Flight::route('POST /dons/@id', ['DonController', 'update']);
Flight::route('GET /dons/@id/delete', ['DonController', 'delete']);

/* ROUTES ACHATS */
Flight::route('GET /achats', ['AchatController', 'index']);
Flight::route('GET /achats/create', ['AchatController', 'create']);
Flight::route('POST /achats', ['AchatController', 'store']);
Flight::route('GET /achats/recapitulatif', ['AchatController', 'recapitulatif']);

/* API ROUTES POUR ACHATS (AJAX) */
Flight::route('GET /api/achats/besoins/@id_ville', ['AchatController', 'getBesoinsByVille']);
Flight::route('GET /api/achats/totaux', ['AchatController', 'actualiserTotaux']);

Flight::start();
