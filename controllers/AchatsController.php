<?php
require_once __DIR__ . '/../config/config.php';

class AchatsController {

    public static function index() {
        try {
            $db = getDB();

            // Récupérer toutes les villes pour le filtre
            $villes = $db->query("SELECT v.id, v.nom, r.nom as region_nom 
                                  FROM ville v
                                  JOIN regions r ON v.id_region = r.id
                                  ORDER BY r.nom, v.nom")->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les achats filtrés si ville_id fourni
            $ville_id = $_GET['ville_id'] ?? '';
            $sql = "SELECT a.*, v.nom as ville_nom, r.nom as region_nom
                    FROM achats a
                    JOIN ville v ON a.id_ville = v.id
                    JOIN regions r ON v.id_region = r.id";
            if ($ville_id) {
                $sql .= " WHERE a.id_ville = :ville_id";
            }
            $sql .= " ORDER BY a.date_achat DESC";

            $stmt = $db->prepare($sql);
            if ($ville_id) {
                $stmt->execute(['ville_id' => $ville_id]);
            } else {
                $stmt->execute();
            }

            $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            include __DIR__ . '/../views/achats/index.php';

        } catch (Exception $e) {
            error_log("Erreur affichage achats: " . $e->getMessage());
            echo "Erreur système, impossible d'afficher les achats.";
        }
    }

    // Réinitialiser tous les achats
    public static function reset() {
        try {
            $db = getDB();
            $db->exec("TRUNCATE TABLE achats");
            header('Location: ' . BASE_URL . '/achats?success=reset');
            exit;
        } catch (Exception $e) {
            error_log("Erreur reset achats: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/achats?error=reset');
            exit;
        }
    }
}
?>
