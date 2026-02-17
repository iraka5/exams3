<?php 
require_once __DIR__ . '/../config/config.php';

class ConfigController {
    
    /**
     * Afficher le formulaire de configuration du taux
     */
    public static function showConfigForm() {
        // Accessible à tous les utilisateurs
        
        try {
            $db = getDB();
            
            // Récupérer le taux actuel et autres paramètres
            $parametres = $db->query("SELECT * FROM parametres ORDER BY cle")->fetchAll(PDO::FETCH_ASSOC);
            
            // Récupérer quelques statistiques de vente si disponibles
            $stats_vente = $db->query("
                SELECT 
                    COUNT(*) as total_ventes,
                    SUM(prix_original) as valeur_originale_totale,
                    SUM(prix_vente) as valeur_vente_totale,
                    AVG((prix_original - prix_vente) / prix_original * 100) as taux_moyen_realise
                FROM dons 
                WHERE vendu = TRUE AND prix_original > 0 AND prix_vente > 0
            ")->fetch(PDO::FETCH_ASSOC);
            
            // Récupérer les dernières ventes pour référence
            $dernieres_ventes = $db->query("
                SELECT d.type_don, d.nom_donneur, d.prix_original, d.prix_vente, d.date_vente, v.nom as ville_nom,
                       ((d.prix_original - d.prix_vente) / d.prix_original * 100) as taux_applique
                FROM dons d
                JOIN ville v ON d.id_ville = v.id  
                WHERE d.vendu = TRUE
                ORDER BY d.date_vente DESC
                LIMIT 10
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            include __DIR__ . '/../views/config_taux.php';
            
        } catch (Exception $e) {
            error_log("Erreur affichage config: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/?error=config_erreur');
            exit;
        }
    }
    
    /**
     * Mettre à jour le taux de diminution
     */
    public static function updateTaux() {
        // Accessible à tous les utilisateurs
        
        $nouveau_taux = $_POST['taux_diminution'] ?? '';
        
        // Validation
        if (!is_numeric($nouveau_taux) || $nouveau_taux < 0 || $nouveau_taux > 100) {
            header('Location: ' . BASE_URL . '/config-taux?error=taux_invalide');
            exit;
        }
        
        try {
            $db = getDB();
            
            // Récupérer l'ancien taux pour le log
            $stmt = $db->prepare("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
            $stmt->execute();
            $ancien_taux = $stmt->fetch(PDO::FETCH_ASSOC);
            $ancien_taux = $ancien_taux ? $ancien_taux['valeur'] : '10';
            
            // Mettre à jour le taux
            $stmt = $db->prepare("UPDATE parametres SET 
                                valeur = ?, 
                                updated_at = NOW() 
                                WHERE cle = 'taux_diminution_vente'");
            $stmt->execute([$nouveau_taux]);
            
            // Si le paramètre n'existait pas, le créer
            if ($stmt->rowCount() == 0) {
                $stmt = $db->prepare("INSERT INTO parametres (cle, valeur, description, type) VALUES 
                                    ('taux_diminution_vente', ?, 'Pourcentage de diminution appliqué lors de la vente d\'articles', 'integer')");
                $stmt->execute([$nouveau_taux]);
            }
            
            // Log de l'opération
            error_log("Taux de diminution modifié: $ancien_taux% -> $nouveau_taux%");
            
            header('Location: ' . BASE_URL . '/config-taux?success=taux_modifie&ancien=' . $ancien_taux . '&nouveau=' . $nouveau_taux);
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur mise à jour taux: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/config-taux?error=erreur_sauvegarde');
            exit;
        }
    }
    
    /**
     * API pour récupérer le taux actuel
     */
    public static function getTauxApi() {
        header('Content-Type: application/json');
        
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT valeur, updated_at FROM parametres WHERE cle = 'taux_diminution_vente'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'taux' => (float)$result['valeur'],
                    'derniere_modification' => $result['updated_at']
                ]);
            } else {
                // Créer le paramètre avec la valeur par défaut
                $stmt = $db->prepare("INSERT INTO parametres (cle, valeur, description, type) VALUES 
                                    ('taux_diminution_vente', '10', 'Pourcentage de diminution appliqué lors de la vente d\'articles', 'integer')");
                $stmt->execute();
                
                echo json_encode([
                    'success' => true,
                    'taux' => 10.0,
                    'derniere_modification' => date('Y-m-d H:i:s'),
                    'created' => true
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * API pour récupérer tous les paramètres configurables  
     */
    public static function getAllParametersApi() {
        header('Content-Type: application/json');
        
        try {
            $db = getDB();
            $parametres = $db->query("SELECT * FROM parametres ORDER BY cle")->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'parametres' => $parametres,
                'count' => count($parametres)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Simuler l'impact d'un changement de taux sur les ventes potentielles
     */
    public static function simulerImpactTaux($nouveau_taux) {
        try {
            $db = getDB();
            
            // Récupérer les dons non vendus et vendables potentiels
            $dons_vendables = $db->query("
                SELECT d.id, d.type_don, d.prix_original, v.nom as ville_nom
                FROM dons d
                JOIN ville v ON d.id_ville = v.id  
                WHERE d.vendu = FALSE 
                AND d.prix_original IS NOT NULL 
                AND d.prix_original > 0
                AND d.type_don NOT IN (
                    SELECT DISTINCT b.nom 
                    FROM besoins b 
                    WHERE b.essentiel = TRUE
                )
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            $simulation = [
                'dons_analysés' => count($dons_vendables),
                'valeur_originale_totale' => 0,
                'valeur_vente_ancien_taux' => 0,
                'valeur_vente_nouveau_taux' => 0,
                'details' => []
            ];
            
            $ancien_taux = self::getTauxActuel();
            
            foreach ($dons_vendables as $don) {
                $prix_original = $don['prix_original'];
                $prix_ancien = $prix_original * (1 - $ancien_taux / 100);
                $prix_nouveau = $prix_original * (1 - $nouveau_taux / 100);
                
                $simulation['valeur_originale_totale'] += $prix_original;
                $simulation['valeur_vente_ancien_taux'] += $prix_ancien;
                $simulation['valeur_vente_nouveau_taux'] += $prix_nouveau;
                
                $simulation['details'][] = [
                    'don' => $don,
                    'prix_vente_ancien' => $prix_ancien,
                    'prix_vente_nouveau' => $prix_nouveau,
                    'difference' => $prix_nouveau - $prix_ancien
                ];
            }
            
            $simulation['difference_totale'] = $simulation['valeur_vente_nouveau_taux'] - $simulation['valeur_vente_ancien_taux'];
            $simulation['ancien_taux'] = $ancien_taux;
            $simulation['nouveau_taux'] = $nouveau_taux;
            
            return $simulation;
            
        } catch (Exception $e) {
            error_log("Erreur simulation taux: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Récupérer le taux actuel (méthode helper)
     */
    private static function getTauxActuel() {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (float)$result['valeur'] : 10.0;
            
        } catch (Exception $e) {
            return 10.0; // Valeur par défaut
        }
    }
}
?>