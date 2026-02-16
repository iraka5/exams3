<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/RegionController.php';
require_once __DIR__ . '/controllers/VilleController.php';
require_once __DIR__ . '/controllers/BesoinController.php';
require_once __DIR__ . '/controllers/DonController.php';

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

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
    if (!isAuthenticated()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    Flight::render('tableau_bord_simple');
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
    Flight::render('tableau_bord_simple');
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

/* ROUTE DECONNEXION */
Flight::route('GET /logout', function(){
    session_destroy();
    Flight::redirect('/exams3-main/exams3/login');
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

Flight::start();
