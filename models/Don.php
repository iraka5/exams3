<?php
require_once __DIR__ . "/../config/config.php";

class Don
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT dons.*, ville.nom AS ville_nom
                FROM dons
                JOIN ville ON dons.id_ville = ville.id
                ORDER BY dons.id DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nom_donneur, $type_don, $nombre_don, $id_ville)
    {
        $db = getDB();
        $sql = "INSERT INTO dons(nom_donneur, type_don, nombre_don, id_ville)
                VALUES(?,?,?,?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
    }

    public static function find($id)
    {
        $db = getDB();
        $sql = "SELECT dons.*, ville.nom AS ville_nom
                FROM dons
                JOIN ville ON dons.id_ville = ville.id
                WHERE dons.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $nom_donneur, $type_don, $nombre_don, $id_ville)
    {
        $db = getDB();
        $sql = "UPDATE dons SET nom_donneur = ?, type_don = ?, nombre_don = ?, id_ville = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville, $id]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $sql = "DELETE FROM dons WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
