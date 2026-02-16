<?php
class DashboardController {
    public static function getStats() {
        $db = Database::getConnection();
        $sql = "SELECT r.nom AS region,
                       v.nom AS ville,
                       b.nom AS besoin,
                       b.nombre AS quantite_demandee,
                       COALESCE(SUM(d.quantite), 0) AS quantite_donnee
                FROM besoins b
                JOIN villes v ON b.id_ville = v.id
                JOIN regions r ON v.id_regions = r.id
                LEFT JOIN dons d ON d.id_besoin = b.id
                GROUP BY r.nom, v.nom, b.nom, b.nombre";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
