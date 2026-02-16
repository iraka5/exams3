<?php
require_once __DIR__ . "/../config/config.php";

class Besoin
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT besoins.*, villes.nom AS ville_nom
                FROM besoins
                JOIN villes ON besoins.ville_id = villes.id
                ORDER BY besoins.id DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allByVille($ville_id)
    {
        $db = getDB();
        $sql = "SELECT besoins.*, villes.nom AS ville_nom
                FROM besoins
                JOIN villes ON besoins.ville_id = villes.id
                WHERE besoins.ville_id = ?
                ORDER BY besoins.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$ville_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nom, $nombre, $ville_id)
    {
        $db = getDB();
        $sql = "INSERT INTO besoins(nom, nombre, ville_id) VALUES(?,?,?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $nombre, $ville_id]);
    }
}
