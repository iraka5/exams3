<?php
require_once __DIR__ . "/../config/config.php";

class Besoin
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT besoins.*, ville.nom AS ville_nom
                FROM besoins
                JOIN ville ON besoins.id_ville = ville.id
                ORDER BY besoins.id DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allByVille($id_ville)
    {
        $db = getDB();
        $sql = "SELECT besoins.*, ville.nom AS ville_nom
                FROM besoins
                JOIN ville ON besoins.id_ville = ville.id
                WHERE besoins.id_ville = ?
                ORDER BY besoins.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nom, $nombre, $prix_unitaire, $type_besoin, $id_ville)
    {
        $db = getDB();
        $sql = "INSERT INTO besoins(nom, nombre, prix_unitaire, type_besoin, id_ville) VALUES(?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $nombre, $prix_unitaire, $type_besoin, $id_ville]);
    }

    public static function find($id)
    {
        $db = getDB();
        $sql = "SELECT besoins.*, ville.nom AS ville_nom
                FROM besoins
                JOIN ville ON besoins.id_ville = ville.id
                WHERE besoins.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $nom, $nombre, $prix_unitaire, $type_besoin, $id_ville)
    {
        $db = getDB();
        $sql = "UPDATE besoins SET nom = ?, nombre = ?, prix_unitaire = ?, type_besoin = ?, id_ville = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom, $nombre, $prix_unitaire, $type_besoin, $id_ville, $id]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $sql = "DELETE FROM besoins WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
