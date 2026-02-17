<?php
// config/config.php

// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Constante pour l'URL de base (utilisée dans les redirections)
define('BASE_URL', '/exams3-main/exams3');

function getDB()
{
    static $db = null;

    if ($db === null) {
        $host = "localhost";
        $dbname = "4191_4194_4222";
        $user = "root";
        $pass = ""; 

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur connexion DB : " . $e->getMessage());
        }
    }

    return $db;
}
?>