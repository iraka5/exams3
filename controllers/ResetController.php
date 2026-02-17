<?php
require_once __DIR__ . '/../config/config.php';

class ResetController {
    
    /**
     * Afficher le formulaire de réinitialisation des données
     */
    public static function showResetForm() {
        // Accessible à tous les utilisateurs
        
        $db = getDB();
        
        // Vérifier si les tables V3 existent, sinon les créer
        try {
            $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            // Vérifier table parametres
            if (!in_array('parametres', $tables)) {
                echo "<!-- Création de la table parametres -->";
                $db->exec("CREATE TABLE `parametres` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `cle` VARCHAR(100) NOT NULL UNIQUE,
                    `valeur` TEXT NOT NULL,
                    `description` TEXT,
                    `type` ENUM('integer', 'decimal', 'text', 'boolean') NOT NULL DEFAULT 'text',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )");
                
                $db->exec("INSERT IGNORE INTO `parametres` (`cle`, `valeur`, `description`, `type`) VALUES
                ('taux_diminution_vente', '10', 'Pourcentage de diminution appliqué lors de la vente d\\'articles (défaut: 10%)', 'integer')");
            }
            
            // Vérifier champs V3 dans besoins
            $columns = $db->query("SHOW COLUMNS FROM besoins")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('essentiel', $columns)) {
                echo "<!-- Ajout du champ essentiel dans besoins -->";
                $db->exec("ALTER TABLE `besoins` ADD COLUMN `essentiel` BOOLEAN NOT NULL DEFAULT FALSE");
            }
            
            // Vérifier champs V3 dans dons
            $columns = $db->query("SHOW COLUMNS FROM dons")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('vendu', $columns)) {
                echo "<!-- Ajout des champs V3 dans dons -->";
                $db->exec("ALTER TABLE `dons` 
                    ADD COLUMN `vendu` BOOLEAN NOT NULL DEFAULT FALSE,
                    ADD COLUMN `prix_original` DECIMAL(10,2) DEFAULT NULL,
                    ADD COLUMN `prix_vente` DECIMAL(10,2) DEFAULT NULL,
                    ADD COLUMN `date_vente` TIMESTAMP NULL DEFAULT NULL");
            }
            
        } catch (Exception $e) {
            // En cas d'erreur, on continue quand même
            error_log("Erreur initialisation V3: " . $e->getMessage());
        }
        
        $db = getDB();
        
        // Récupérer les statistiques actuelles
        $stats = [
            'regions' => $db->query("SELECT COUNT(*) FROM regions")->fetchColumn(),
            'villes' => $db->query("SELECT COUNT(*) FROM ville")->fetchColumn(), 
            'besoins' => $db->query("SELECT COUNT(*) FROM besoins")->fetchColumn(),
            'dons' => $db->query("SELECT COUNT(*) FROM dons")->fetchColumn(),
            'echanges' => $db->query("SELECT COUNT(*) FROM echange")->fetchColumn(),
            'achats' => $db->query("SELECT COUNT(*) FROM achats")->fetchColumn()
        ];
        
        // Récupérer quelques besoins et dons récents pour info
        $besoins_recents = $db->query("SELECT b.nom, v.nom as ville_nom, b.created_at 
                                     FROM besoins b 
                                     JOIN ville v ON b.id_ville = v.id 
                                     ORDER BY b.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        
        $dons_recents = $db->query("SELECT d.type_don, d.nom_donneur, v.nom as ville_nom, d.created_at
                                  FROM dons d 
                                  JOIN ville v ON d.id_ville = v.id 
                                  ORDER BY d.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/../views/reset_form.php';
    }
    
    /**
     * Exécuter la réinitialisation des données
     */
    public static function executeReset() {
        // Accessible à tous les utilisateurs
        
        // Vérifier le token CSRF si implémenté
        $confirm = $_POST['confirm'] ?? '';
        if ($confirm !== 'RESET_DATA') {
            header('Location: ' . BASE_URL . '/reset-data?error=confirmation');
            exit;
        }
        
        try {
            $db = getDB();
            
            // Commencer une transaction
            $db->beginTransaction();
            
            // Lire et exécuter le script de reset
            $sqlScript = file_get_contents(__DIR__ . '/../database/seed_reset.sql');
            
            // Diviser le script en requêtes individuelles
            $queries = array_filter(
                array_map('trim', explode(';', $sqlScript)),
                function($query) {
                    return !empty($query) && !preg_match('/^--/', $query);
                }
            );
            
            $executed = 0;
            foreach ($queries as $query) {
                if (!empty($query) && !preg_match('/^(USE|SELECT.*as.*count)/', trim($query))) {
                    $db->exec($query);
                    $executed++;
                }
            }
            
            // Valider la transaction
            $db->commit();
            
            // Récupérer les nouvelles statistiques
            $nouvelles_stats = [
                'regions' => $db->query("SELECT COUNT(*) FROM regions")->fetchColumn(),
                'villes' => $db->query("SELECT COUNT(*) FROM ville")->fetchColumn(),
                'besoins' => $db->query("SELECT COUNT(*) FROM besoins")->fetchColumn(), 
                'dons' => $db->query("SELECT COUNT(*) FROM dons")->fetchColumn(),
                'echanges' => $db->query("SELECT COUNT(*) FROM echange")->fetchColumn(),
                'achats' => $db->query("SELECT COUNT(*) FROM achats")->fetchColumn()
            ];
            
            // Log de l'opération si besoin
            error_log("Reset des données effectué - Requêtes exécutées: $executed - " . json_encode($nouvelles_stats));
            
            header('Location: ' . BASE_URL . '/reset-data?success=1&queries=' . $executed);
            exit;
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if ($db->inTransaction()) {
                $db->rollback();
            }
            
            error_log("Erreur lors du reset des données: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/reset-data?error=execution&message=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    /**
     * Récupérer les statistiques actuelles (API)
     */
    public static function getStatsApi() {
        header('Content-Type: application/json');
        
        try {
            $db = getDB();
            
            $stats = [
                'regions' => $db->query("SELECT COUNT(*) FROM regions")->fetchColumn(),
                'villes' => $db->query("SELECT COUNT(*) FROM ville")->fetchColumn(),
                'besoins' => $db->query("SELECT COUNT(*) FROM besoins")->fetchColumn(),
                'dons' => $db->query("SELECT COUNT(*) FROM dons")->fetchColumn(),
                'echanges' => $db->query("SELECT COUNT(*) FROM echange")->fetchColumn(),
                'achats' => $db->query("SELECT COUNT(*) FROM achats")->fetchColumn(),
                'derniere_maj' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode(['success' => true, 'stats' => $stats]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        exit;
    }
}
?>