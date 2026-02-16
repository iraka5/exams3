<?php
require_once __DIR__ . "/../models/Region.php";

class RegionController
{
    public static function index()
    {
        $regions = Region::getWithVillesCount();
        
        Flight::render("regions/index", [
            "regions" => $regions
        ]);
    }

    public static function show($id)
    {
        $region = Region::find($id);
        if (!$region) {
            Flight::notFound();
            return;
        }

        require_once __DIR__ . "/../models/Ville.php";
        $villes = Ville::getByRegion($id);
        
        Flight::render("regions/show", [
            "region" => $region,
            "villes" => $villes
        ]);
    }

    public static function createForm()
    {
        Flight::render("regions/create");
    }

    public static function store()
    {
        $nom = trim($_POST["nom"] ?? "");

        if ($nom === "") {
            Flight::redirect("/regions/create?error=1");
            return;
        }

        Region::create($nom);
        Flight::redirect("/regions");
    }

    public static function editForm($id)
    {
        $region = Region::find($id);
        if (!$region) {
            Flight::notFound();
            return;
        }

        Flight::render("regions/edit", [
            "region" => $region
        ]);
    }

    public static function update($id)
    {
        $nom = trim($_POST["nom"] ?? "");

        if ($nom === "") {
            Flight::redirect("/regions/{$id}/edit?error=1");
            return;
        }

        Region::update($id, $nom);
        Flight::redirect("/regions");
    }

    public static function delete($id)
    {
        Region::delete($id);
        Flight::redirect("/regions");
    }
}