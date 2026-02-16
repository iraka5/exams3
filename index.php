<?php
<<<<<<< HEAD
require_once __DIR__ . '/vendor/autoload.php';
=======
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
require __DIR__ . '/vendor/autoload.php';
>>>>>>> 3785f791c53a5417db092127f7e5e5ddbdf8ad0c

// Configuration des vues pour FlightPHP  
Flight::set('flight.views.path', __DIR__ . '/controllers/views');

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
<<<<<<< HEAD
    Flight::redirect('/exams3-main/exams3/tableau-bord');
=======
    Flight::redirect('/views/login.php');
>>>>>>> 6a2a1ad0c7ed3cd3408636c03209ffc19e32984f
});

/* ROUTE TABLEAU DE BORD */
Flight::route('GET /tableau-bord', function(){
    Flight::render('tableau_bord_simple');
});

/* ROUTES SIGNUP */
Flight::route('GET /signup', function(){
    include 'views/signup.php';
});

Flight::route('POST /signup', function(){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Ici tu devrais ajouter une validation et vérifier que l'email n'existe pas déjà
    $db = Database::getConnection();
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    Flight::redirect('/login');
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
