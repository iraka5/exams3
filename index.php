<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LoginController.php';

// Configuration des vues pour FlightPHP  
Flight::set('flight.views.path', __DIR__ . '/views');

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
Flight::route('GET /regions', function(){
    Flight::render('regions_simple');
});

/* ROUTES VILLES */
Flight::route('GET /villes', function(){
    Flight::render('villes_simple');
});

/* ROUTES BESOINS */
Flight::route('GET /besoins', function(){
    Flight::render('besoins_simple');
});

/* ROUTES DONS */
Flight::route('GET /dons', function(){
    Flight::render('dons_simple');
});

Flight::start();
