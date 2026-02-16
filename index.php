<?php
require 'flight/Flight.php';

require_once __DIR__ . "/controllers/BesoinController.php";
require_once __DIR__ . "/controllers/DonController.php";
require_once __DIR__ . "/controllers/LoginController.php";

session_start();

/* ROUTES LOGIN */
Flight::route('GET /login', function(){
    include 'views/login.php';
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

/* ROUTES BESOINS */
Flight::route('GET /besoins', ['BesoinController', 'index']);
Flight::route('GET /besoins/create', ['BesoinController', 'createForm']);
Flight::route('POST /besoins/store', ['BesoinController', 'store']);

/* ROUTES DONS */
Flight::route('GET /dons', ['DonController', 'index']);
Flight::route('GET /dons/create', ['DonController', 'createForm']);
Flight::route('POST /dons/store', ['DonController', 'store']);

Flight::start();
