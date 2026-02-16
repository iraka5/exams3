<?php
require_once __DIR__ . "/../config/config.php";

class Region
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT * FROM regions ORDER BY nom ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = getDB();
        $sql = "SELECT * FROM regions WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($nom)
    {
        $db = getDB();
        $sql = "INSERT INTO regions(nom) VALUES(?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom]);
    }

    public static function update($id, $nom)
    {
        $db = getDB();
        $sql = "UPDATE regions SET nom = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $id]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $sql = "DELETE FROM regions WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function getWithVillesCount()
    {
        $db = getDB();
        $sql = "SELECT regions.*, COUNT(ville.id) as nb_villes 
                FROM regions 
                LEFT JOIN ville ON regions.id = ville.id_regions 
                GROUP BY regions.id 
                ORDER BY regions.nom ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}