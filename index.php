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
Flight::set('flight.views.path', __DIR__ . '/controllers/views');

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
    Flight::redirect('/login');
});

/* ROUTES LOGIN */
Flight::route('GET /login', function(){
    include 'views/login.html';
});

Flight::route('POST /login', function(){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (LoginController::authenticate($email, $password)) {
        if ($_SESSION['role'] === 'admin') {
            Flight::redirect('/tableau-bord'); // admin → tableau de bord
        } else {
            Flight::redirect('/dons'); // user → page dons
        }
    } else {
        echo "Identifiants incorrects";
    }
});

/* ROUTE TABLEAU DE BORD */
Flight::route('GET /tableau-bord', function(){
    Flight::render('tableau_bord_simple');
});


/* ROUTES SIGNUP */
Flight::route('GET /signup', function(){
    include 'views/signup.html';
});

Flight::route('POST /signup', function(){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Vérifier que l'email n'existe pas déjà
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo "Cet email existe déjà !";
            return;
        }

        $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$username, $email, $password]);

        Flight::redirect('/login');
    } catch (Exception $e) {
        echo "Erreur inscription: " . $e->getMessage();
    }
});

/* ROUTES REGIONS */
Flight::route('GET /regions', ['RegionController', 'index']);
Flight::route('GET /regions/create', ['RegionController', 'createForm']);
Flight::route('POST /regions/store', ['RegionController', 'store']);
Flight::route('GET /regions/@id', ['RegionController', 'show']);
Flight::route('GET /regions/@id/edit', ['RegionController', 'editForm']);
Flight::route('POST /regions/@id/update', ['RegionController', 'update']);
Flight::route('GET /regions/@id/delete', ['RegionController', 'delete']);

/* ROUTES VILLES */
Flight::route('GET /villes', ['VilleController', 'index']);
Flight::route('GET /villes/create', ['VilleController', 'createForm']);
Flight::route('POST /villes/store', ['VilleController', 'store']);
Flight::route('GET /villes/@id', ['VilleController', 'show']);
Flight::route('GET /villes/@id/edit', ['VilleController', 'editForm']);
Flight::route('POST /villes/@id/update', ['VilleController', 'update']);
Flight::route('GET /villes/@id/delete', ['VilleController', 'delete']);

/* ROUTES BESOINS */
Flight::route('GET /besoins', ['BesoinController', 'index']);
Flight::route('GET /besoins/create', ['BesoinController', 'createForm']);
Flight::route('POST /besoins/store', ['BesoinController', 'store']);
Flight::route('GET /besoins/@id', ['BesoinController', 'show']);
Flight::route('GET /besoins/@id/edit', ['BesoinController', 'editForm']);
Flight::route('POST /besoins/@id/update', ['BesoinController', 'update']);
Flight::route('GET /besoins/@id/delete', ['BesoinController', 'delete']);

/* ROUTES DONS */
Flight::route('GET /dons', ['DonController', 'index']);
Flight::route('GET /dons/create', ['DonController', 'createForm']);
Flight::route('POST /dons/store', ['DonController', 'store']);
Flight::route('GET /dons/@id', ['DonController', 'show']);
Flight::route('GET /dons/@id/edit', ['DonController', 'editForm']);
Flight::route('POST /dons/@id/update', ['DonController', 'update']);
Flight::route('GET /dons/@id/delete', ['DonController', 'delete']);

Flight::start();
