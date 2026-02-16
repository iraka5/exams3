<?php
// config/config.php

function getDB()
{
    static $db = null;

    if ($db === null) {
        $host = "localhost";
        $dbname = "4191_4194_4222";
        $user = "root";
        $pass = ""; // change si ton mysql a un mot de passe

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur connexion DB : " . $e->getMessage());
        }
    }

    return $db;
}
