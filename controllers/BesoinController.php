<?php
require_once __DIR__ . "/../models/Besoin.php";
require_once __DIR__ . "/../config/config.php";

class BesoinController
{
    public static function index()
    {
        $db = getDB();
        $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        $id_ville = isset($_GET["id_ville"]) ? intval($_GET["id_ville"]) : 0;

        if ($id_ville > 0) {
            $besoins = Besoin::allByVille($id_ville);
        } else {
            $besoins = Besoin::all();
        }

        Flight::render("besoins/index", [
            "besoins" => $besoins,
            "villes" => $villes,
            "id_ville" => $id_ville
        ]);
    }

    public static function createForm()
    {
        $db = getDB();
        $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        Flight::render("besoins/create", [
            "villes" => $villes
        ]);
    }

    public static function store()
    {
        $nom = trim($_POST["nom"] ?? "");
        $nombre = floatval($_POST["nombre"] ?? 0);
        $id_ville = intval($_POST["id_ville"] ?? 0);

        if ($nom === "" || $nombre <= 0 || $id_ville <= 0) {
            Flight::redirect("/besoins/create?error=1");
            return;
        }

        Besoin::create($nom, $nombre, $id_ville);
        Flight::redirect("/besoins");
    }
}
