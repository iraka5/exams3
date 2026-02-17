<?php
// index.php (propre)

// Démarrer la session
session_start();

// Afficher les erreurs (debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Charger Composer (Flight, etc.)
require __DIR__ . '/vendor/autoload.php';

// Charger la config DB + fonctions
require_once __DIR__ . '/config/config.php';

// Charger les routes
require_once __DIR__ . '/routes.php';
