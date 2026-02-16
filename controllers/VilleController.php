<?php
require_once __DIR__ . "/../models/Ville.php";
require_once __DIR__ . "/../models/Region.php";

class VilleController
{
    public static function index()
    {
        $region_id = isset($_GET["region_id"]) ? intval($_GET["region_id"]) : 0;
        
        if ($region_id > 0) {
            $villes = Ville::getByRegion($region_id);
            $region = Region::find($region_id);
        } else {
            $villes = Ville::all();
            $region = null;
        }

        $regions = Region::all();
        
        Flight::render("villes/index", [
            "villes" => $villes,
            "regions" => $regions,
            "region_id" => $region_id,
            "region_selected" => $region
        ]);
    }

    public static function show($id)
    {
        $ville = Ville::find($id);
        if (!$ville) {
            Flight::notFound();
            return;
        }

        // Charger les besoins et dons de cette ville
        require_once __DIR__ . "/../config/config.php";
        $db = getDB();
        
        $besoins = $db->query("SELECT * FROM besoins WHERE id_ville = {$id} ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        $dons = $db->query("SELECT * FROM dons WHERE id_ville = {$id} ORDER BY nom_donneur")->fetchAll(PDO::FETCH_ASSOC);
        
        Flight::render("villes/show", [
            "ville" => $ville,
            "besoins" => $besoins,
            "dons" => $dons
        ]);
    }

    public static function createForm()
    {
        $regions = Region::all();
        
        Flight::render("villes/create", [
            "regions" => $regions
        ]);
    }

    public static function store()
    {
        $nom = trim($_POST["nom"] ?? "");
        $id_regions = intval($_POST["id_regions"] ?? 0);

        if ($nom === "" || $id_regions <= 0) {
            Flight::redirect("/villes/create?error=1");
            return;
        }

        Ville::create($nom, $id_regions);
        Flight::redirect("/villes");
    }

    public static function editForm($id)
    {
        $ville = Ville::find($id);
        if (!$ville) {
            Flight::notFound();
            return;
        }

        $regions = Region::all();
        
        Flight::render("villes/edit", [
            "ville" => $ville,
            "regions" => $regions
        ]);
    }

    public static function update($id)
    {
        $nom = trim($_POST["nom"] ?? "");
        $id_regions = intval($_POST["id_regions"] ?? 0);

        if ($nom === "" || $id_regions <= 0) {
            Flight::redirect("/villes/{$id}/edit?error=1");
            return;
        }

        Ville::update($id, $nom, $id_regions);
        Flight::redirect("/villes");
    }

    public static function delete($id)
    {
        Ville::delete($id);
        Flight::redirect("/villes");
    }

    public static function tableau()
    {
        $villes = Ville::getWithBesoinsAndDons();
        
        Flight::render("villes/tableau", [
            "villes" => $villes
        ]);
    }
}