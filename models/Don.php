<?php
require_once __DIR__ . "/../config/config.php";

class Don
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT dons.*, villes.nom AS ville_nom
                FROM dons
                JOIN villes ON dons.ville_id = villes.id
                ORDER BY dons.id DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nom_donneur, $type_don, $nombre_don, $ville_id)
    {
        $db = getDB();
        $sql = "INSERT INTO dons(nom_donneur, type_don, nombre_don, ville_id)
                VALUES(?,?,?,?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$nom_donneur, $type_don, $nombre_don, $ville_id]);
    }
}
