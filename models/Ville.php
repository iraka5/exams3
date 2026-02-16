<?php
require_once __DIR__ . "/../config/config.php";

class Ville
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT ville.*, regions.nom as region_nom 
                FROM ville 
                JOIN regions ON ville.id_regions = regions.id 
                ORDER BY regions.nom ASC, ville.nom ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = getDB();
        $sql = "SELECT ville.*, regions.nom as region_nom 
                FROM ville 
                JOIN regions ON ville.id_regions = regions.id 
                WHERE ville.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByRegion($region_id)
    {
        $db = getDB();
        $sql = "SELECT ville.*, regions.nom as region_nom 
                FROM ville 
                JOIN regions ON ville.id_regions = regions.id 
                WHERE ville.id_regions = ? 
                ORDER BY ville.nom ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$region_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nom, $id_regions)
    {
        $db = getDB();
        $sql = "INSERT INTO ville(nom, id_regions) VALUES(?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $id_regions]);
    }

    public static function update($id, $nom, $id_regions)
    {
        $db = getDB();
        $sql = "UPDATE ville SET nom = ?, id_regions = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $id_regions, $id]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $sql = "DELETE FROM ville WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function getWithBesoinsAndDons()
    {
        $db = getDB();
        $sql = "SELECT ville.*, regions.nom as region_nom,
                COUNT(DISTINCT besoins.id) as nb_besoins,
                COUNT(DISTINCT dons.id) as nb_dons,
                COALESCE(SUM(DISTINCT besoins.nombre), 0) as total_besoins,
                COALESCE(SUM(DISTINCT dons.nombre_don), 0) as total_dons
                FROM ville 
                JOIN regions ON ville.id_regions = regions.id 
                LEFT JOIN besoins ON ville.id = besoins.id_ville
                LEFT JOIN dons ON ville.id = dons.id_ville
                GROUP BY ville.id 
                ORDER BY regions.nom ASC, ville.nom ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}