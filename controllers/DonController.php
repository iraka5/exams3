<?php
require_once __DIR__ . "/../models/Don.php";
require_once __DIR__ . "/../config/config.php";

class DonController
{
    public static function index()
    {
        $dons = Don::all();
        Flight::render("dons/index", [
            "dons" => $dons
        ]);
    }

    public static function createForm()
    {
        $db = getDB();
        $villes = $db->query("SELECT * FROM villes ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        Flight::render("dons/create", [
            "villes" => $villes
        ]);
    }

    public static function store()
    {
        $nom_donneur = trim($_POST["nom_donneur"] ?? "");
        $type_don = trim($_POST["type_don"] ?? "");
        $nombre_don = intval($_POST["nombre_don"] ?? 0);
        $ville_id = intval($_POST["ville_id"] ?? 0);

        if ($nom_donneur === "" || $type_don === "" || $nombre_don <= 0 || $ville_id <= 0) {
            Flight::redirect("/dons/create?error=1");
            return;
        }

        Don::create($nom_donneur, $type_don, $nombre_don, $ville_id);
        Flight::redirect("/dons");
    }
}
