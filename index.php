<?php
require_once __DIR__ . '/vendor/autoload.php';

// Configuration des vues pour FlightPHP  
Flight::set('flight.views.path', __DIR__ . '/controllers/views');

/* ROUTES ACCUEIL */
Flight::route('GET /', function(){
    Flight::redirect('/tableau-bord');
});

/* ROUTE TABLEAU DE BORD */
Flight::route('GET /tableau-bord', function(){
    Flight::render('tableau_bord_simple');
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
