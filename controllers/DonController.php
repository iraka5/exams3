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
        $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        Flight::render("dons/create", [
            "villes" => $villes
        ]);
    }

    public static function store()
    {
        $nom_donneur = trim($_POST["nom_donneur"] ?? "");
        $type_don = trim($_POST["type_don"] ?? "");
        $nombre_don = floatval($_POST["nombre_don"] ?? 0);
        $id_ville = intval($_POST["id_ville"] ?? 0);

        if ($nom_donneur === "" || $type_don === "" || $nombre_don <= 0 || $id_ville <= 0) {
            Flight::redirect("/exams3-main/exams3/dons/create?error=1");
            return;
        }

        Don::create($nom_donneur, $type_don, $nombre_don, $id_ville);
        Flight::redirect("/exams3-main/exams3/dons");
    }

    public static function show($id)
    {
        $don = Don::find($id);
        if (!$don) {
            Flight::redirect("/exams3-main/exams3/dons");
            return;
        }

        Flight::render("dons/show", [
            "don" => $don
        ]);
    }

    public static function editForm($id)
    {
        $don = Don::find($id);
        if (!$don) {
            Flight::redirect("/exams3-main/exams3/dons");
            return;
        }

        $db = getDB();
        $villes = $db->query("SELECT * FROM ville ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

        Flight::render("dons/edit", [
            "don" => $don,
            "villes" => $villes
        ]);
    }

    public static function update($id)
    {
        $don = Don::find($id);
        if (!$don) {
            Flight::redirect("/exams3-main/exams3/dons");
            return;
        }

        $nom_donneur = trim($_POST["nom_donneur"] ?? "");
        $type_don = trim($_POST["type_don"] ?? "");
        $nombre_don = floatval($_POST["nombre_don"] ?? 0);
        $id_ville = intval($_POST["id_ville"] ?? 0);

        if ($nom_donneur === "" || $type_don === "" || $nombre_don <= 0 || $id_ville <= 0) {
            Flight::redirect("/exams3-main/exams3/dons/$id/edit?error=1");
            return;
        }

        Don::update($id, $nom_donneur, $type_don, $nombre_don, $id_ville);
        Flight::redirect("/exams3-main/exams3/dons");
    }

    public static function delete($id)
    {
        $don = Don::find($id);
        if (!$don) {
            Flight::redirect("/exams3-main/exams3/dons");
            return;
        }

        Don::delete($id);
        Flight::redirect("/exams3-main/exams3/dons");
    }
}
