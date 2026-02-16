<?php
require_once __DIR__ . "/../models/Besoin.php";
require_once __DIR__ . "/../config/config.php";

class BesoinController
{
    public static function index()
    {
        $db = getDB();
        $villes = $db->query("SELECT * FROM villes ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        $ville_id = isset($_GET["ville_id"]) ? intval($_GET["ville_id"]) : 0;

        if ($ville_id > 0) {
            $besoins = Besoin::allByVille($ville_id);
        } else {
            $besoins = Besoin::all();
        }

        Flight::render("besoins/index", [
            "besoins" => $besoins,
            "villes" => $villes,
            "ville_id" => $ville_id
        ]);
    }

    public static function createForm()
    {
        $db = getDB();
        $villes = $db->query("SELECT * FROM villes ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        Flight::render("besoins/create", [
            "villes" => $villes
        ]);
    }

    public static function store()
    {
        $nom = trim($_POST["nom"] ?? "");
        $nombre = intval($_POST["nombre"] ?? 0);
        $ville_id = intval($_POST["ville_id"] ?? 0);

        if ($nom === "" || $nombre <= 0 || $ville_id <= 0) {
            Flight::redirect("/besoins/create?error=1");
            return;
        }

        Besoin::create($nom, $nombre, $ville_id);
        Flight::redirect("/besoins");
    }
}
