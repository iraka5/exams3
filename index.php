<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LoginController.php';

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
    Flight::redirect('/login');
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
    if (!isAdmin()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    Flight::render('admin_dashboard');
});

/* ROUTE DECONNEXION */
Flight::route('GET /logout', function(){
    session_destroy();
    Flight::redirect('/exams3-main/exams3/login');
});


/* ROUTES SIGNUP */
Flight::route('GET /signup', function(){
    include 'views/signup.html';
});

// Compatibilité ancien lien direct /signup.html
Flight::route('GET /signup.html', function(){
    Flight::redirect('/signup');
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
    if (!isAdmin()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    Flight::render('regions_simple');
});

Flight::route('POST /regions', function(){
    if (!isAdmin()) {
        Flight::halt(403, 'Accès refusé');
    }
    
    try {
        $nom = $_POST['nom'];
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO regions (nom) VALUES (?)");
        $stmt->execute([$nom]);
        Flight::redirect('/exams3-main/exams3/regions');
    } catch (Exception $e) {
        echo "Erreur ajout région: " . $e->getMessage();
    }
});

/* ROUTES VILLES */
Flight::route('GET /villes', function(){
    if (!isAdmin()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    Flight::render('villes_simple');
});

Flight::route('POST /villes', function(){
    if (!isAdmin()) {
        Flight::halt(403, 'Accès refusé');
    }
    
    try {
        $nom = $_POST['nom'];
        $id_regions = $_POST['id_regions'];
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO ville (nom, id_regions) VALUES (?, ?)");
        $stmt->execute([$nom, $id_regions]);
        Flight::redirect('/exams3-main/exams3/villes');
    } catch (Exception $e) {
        echo "Erreur ajout ville: " . $e->getMessage();
    }
});

/* ROUTES BESOINS */
Flight::route('GET /besoins', function(){
    if (!isAdmin()) {
        Flight::redirect('/exams3-main/exams3/login');
        return;
    }
    Flight::render('besoins_simple');
});

Flight::route('POST /besoins', function(){
    if (!isAdmin()) {
        Flight::halt(403, 'Accès refusé');
    }
    
    try {
        $nom = $_POST['nom'];
        $nombre = $_POST['nombre'];
        $id_ville = $_POST['id_ville'];
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO besoins (nom, nombre, id_ville) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $nombre, $id_ville]);
        Flight::redirect('/exams3-main/exams3/besoins');
    } catch (Exception $e) {
        echo "Erreur ajout besoin: " . $e->getMessage();
    }
});

/* ROUTES DONS */
Flight::route('GET /dons', function(){
    Flight::render('dons_simple');
});

Flight::start();
