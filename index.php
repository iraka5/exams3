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

// Helpers
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function isAuthenticated() {
    return isset($_SESSION['user']);
}
function isUser() {
    return isset($_SESSION['user']) && $_SESSION['role'] === 'user';
}
function requireUserAuth() {
    if (!isUser()) {
        Flight::redirect('/exams3-main/exams3/user/login');
        return false;
    }
    return true;
}

/* ROUTE ACCUEIL */
Flight::route('GET /', function(){
    if (!isAuthenticated()) {
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

/* ROUTES LOGIN (ADMIN + USER) */
Flight::route('GET /login', function(){
    include __DIR__ . '/views/login.html';
});
Flight::route('GET /login.html', function(){
    Flight::redirect('/login');
});
Flight::route('POST /login', function(){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (LoginController::authenticate($email, $password)) {
        if ($_SESSION['role'] === 'admin') {
            Flight::redirect('/tableau-bord');
        } else {
            Flight::redirect('/user/dashboard');
        }
    } else {
        echo "Identifiants incorrects";
    }
});

/* ROUTES SIGNUP */
Flight::route('GET /signup', function(){
    include __DIR__ . '/views/signup.html';
});
Flight::route('GET /signup.html', function(){
    Flight::redirect('/signup');
});
Flight::route('POST /signup', ['UserController', 'register']);


/* ROUTES LOGIN UTILISATEUR (optionnel si tu veux un login séparé) */
Flight::route('GET /user/login', ['UserController', 'loginForm']);
Flight::route('POST /user/login', ['UserController', 'authenticate']);

Flight::route('GET /user/logout', ['UserController', 'logout']);
Flight::route('GET /user/dashboard', ['UserController', 'dashboard']);

/* ROUTE TABLEAU DE BORD ADMIN */
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

/* ROUTE CREATION ADMIN */
Flight::route('GET /create', function(){
    if (!isAdmin()) {
        Flight::redirect('/login');
        return;
    }
    try {
        $db = getDB();
        $regions = $db->query("SELECT id, nom FROM regions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $villes = $db->query("SELECT id, nom FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $regions = [];
        $villes = [];
    }
    Flight::render('create', ['regions' => $regions, 'villes' => $villes]);
});

/* ROUTE LOGOUT */
Flight::route('GET /logout', function(){
    session_unset();
    session_destroy();
    Flight::redirect('/login');
});

/* ROUTES UTILISATEURS (lecture et actions simples) */
Flight::route('GET /user/besoins', function(){
    if (!requireUserAuth()) return;
    $db = getDB();
    $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
    $id_ville = isset($_GET["id_ville"]) ? intval($_GET["id_ville"]) : 0;

    if ($id_ville > 0) {
        $stmt = $db->prepare("SELECT b.*, v.nom AS ville_nom 
                              FROM besoins b 
                              JOIN ville v ON b.id_ville = v.id 
                              WHERE b.id_ville = ? ORDER BY b.id DESC");
        $stmt->execute([$id_ville]);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $besoins = $db->query("SELECT b.*, v.nom AS ville_nom 
                               FROM besoins b 
                               JOIN ville v ON b.id_ville = v.id 
                               ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    include __DIR__ . '/views/users/besoins.php';
});

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
        Flight::redirect("/user/dons?error=1");
        return;
    }
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO dons(nom_donneur, type_don, nombre_don, id_ville) VALUES(?,?,?,?)");
    $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
    Flight::redirect("/user/dashboard?success=don");
});

Flight::route('GET /user/villes', function(){
    if (!requireUserAuth()) return;
    $db = getDB();
    $sql = "SELECT v.id, v.nom AS ville_nom, r.nom AS region_nom,
                   COUNT(DISTINCT b.id) AS nb_besoins,
                   COUNT(DISTINCT d.id) AS nb_dons,
                   COALESCE(SUM(b.nombre), 0) AS total_besoins,
                   COALESCE(SUM(d.nombre_don), 0) AS total_dons
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
