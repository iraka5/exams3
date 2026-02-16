<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . "/controllers/BesoinController.php";
require_once __DIR__ . "/controllers/DonController.php";
require_once __DIR__ . "/controllers/loginControllers.php";
require_once __DIR__ . "/controllers/RegionController.php";
require_once __DIR__ . "/controllers/VilleController.php";

session_start();

// Configuration des vues pour FlightPHP
Flight::set('flight.views.path', __DIR__ . '/controllers/views');

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
    Flight::redirect('/tableau-bord');
});

/* ROUTE DE TEST */
Flight::route('GET /test', function(){
    Flight::render('test');
});

/* ROUTES LOGIN */
Flight::route('GET /login', function(){
    Flight::render('login');
});

Flight::route('POST /login', function(){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (LoginController::authenticate($email, $password)) {
        Flight::redirect('/besoins'); // ou /dashboard selon ton choix
    } else {
        echo "Identifiants incorrects";
    }
});

/* ROUTES REGIONS */
Flight::route('GET /regions', function(){
    Flight::render('regions_simple');
});
Flight::route('GET /regions/create', ['RegionController', 'createForm']);
Flight::route('POST /regions/store', ['RegionController', 'store']);
Flight::route('GET /regions/@id', ['RegionController', 'show']);
Flight::route('GET /regions/@id/edit', ['RegionController', 'editForm']);
Flight::route('POST /regions/@id/update', ['RegionController', 'update']);
Flight::route('GET /regions/@id/delete', ['RegionController', 'delete']);

/* ROUTES VILLES */
Flight::route('GET /villes', function(){
    Flight::render('villes_simple');
});
Flight::route('GET /villes/create', ['VilleController', 'createForm']);
Flight::route('POST /villes/store', ['VilleController', 'store']);
Flight::route('GET /villes/@id', ['VilleController', 'show']);
Flight::route('GET /villes/@id/edit', ['VilleController', 'editForm']);
Flight::route('POST /villes/@id/update', ['VilleController', 'update']);
Flight::route('GET /villes/@id/delete', ['VilleController', 'delete']);

/* ROUTE TABLEAU DE BORD */
Flight::route('GET /tableau-bord', function(){
    Flight::render('tableau_bord_simple');
});

/* ROUTES BESOINS */
Flight::route('GET /besoins', function(){
    Flight::render('besoins_simple');
});
Flight::route('GET /besoins/create', ['BesoinController', 'createForm']);
Flight::route('POST /besoins/store', ['BesoinController', 'store']);

/* ROUTES DONS */
Flight::route('GET /dons', function(){
    Flight::render('dons_simple');
});
Flight::route('GET /dons/create', ['DonController', 'createForm']);
Flight::route('POST /dons/store', ['DonController', 'store']);

Flight::start();
