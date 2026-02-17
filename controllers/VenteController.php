<?php
require_once __DIR__ . '/../config/config.php';

class VenteController {
    
    /**
     * Afficher le formulaire de vente pour un don  
     */
    public static function showSaleForm($don_id) {
        try {
            $db = getDB();
            
            // Récupérer les détails du don
            $stmt = $db->prepare("SELECT d.*, v.nom as ville_nom 
                                FROM dons d 
                                JOIN ville v ON d.id_ville = v.id 
                                WHERE d.id = ? AND d.vendu = FALSE");
            $stmt->execute([$don_id]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                header('Location: ' . BASE_URL . '/dons?error=don_introuvable');
                exit;
            }
            
            // Vérifier si l'article est vendable
            $vendabilite = self::checkVendabiliteInterne($don_id);
            
            // Récupérer le taux de diminution configurable
            $taux_diminution = self::getTauxDiminution();
            
            // Calculer le prix de vente
            $prix_original = $don['prix_original'] ?? 0;
            $prix_vente = $prix_original * (1 - $taux_diminution / 100);
            
            include __DIR__ . '/../views/dons/vente_form.php';
            
        } catch (Exception $e) {
            error_log("Erreur affichage formulaire vente: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/dons?error=erreur_systeme');
            exit;
        }
    }
    
    /**
     * Traiter la vente d'un article
     */
    public static function processSale($don_id) {
        try {
            $db = getDB();
            
            // Vérifier si l'article existe et n'est pas déjà vendu
            $stmt = $db->prepare("SELECT * FROM dons WHERE id = ? AND vendu = FALSE");
            $stmt->execute([$don_id]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                header('Location: ' . BASE_URL . '/dons?error=don_introuvable');
                exit;
            }
            
            // Vérifier si l'article est vendable
            $vendabilite = self::checkVendabiliteInterne($don_id);
            if (!$vendabilite['vendable']) {
                header('Location: ' . BASE_URL . '/dons/' . $don_id . '/vendre?error=vente_non_permise&raison=' . urlencode($vendabilite['raison']));
                exit;
            }
            
            $db->beginTransaction();
            
            // Récupérer le taux de diminution
            $taux_diminution = self::getTauxDiminution();
            $prix_original = $don['prix_original'];
            $prix_vente = $prix_original * (1 - $taux_diminution / 100);
            
            // Marquer l'article comme vendu
            $stmt = $db->prepare("UPDATE dons SET 
                                vendu = TRUE, 
                                prix_vente = ?, 
                                date_vente = NOW() 
                                WHERE id = ?");
            $stmt->execute([$prix_vente, $don_id]);
            
            // Créer un nouveau don en argent avec le montant de la vente
            $stmt = $db->prepare("INSERT INTO dons (nom_donneur, type_don, nombre_don, id_ville, prix_original, vendu) 
                                VALUES (?, 'Argent (Vente)', ?, ?, 1.00, FALSE)");
            $stmt->execute([
                'Vente: ' . $don['nom_donneur'],  // Nom du donneur original avec préfixe
                $prix_vente,                       // Montant de la vente
                $don['id_ville']                  // Même ville
            ]);
            
            $nouveau_don_id = $db->lastInsertId();
            
            $db->commit();
            
            // Log de l'opération
            error_log("Vente effectuée - Don ID: $don_id, Prix original: $prix_original, Prix vente: $prix_vente, Nouveau don: $nouveau_don_id");
            
            header('Location: ' . BASE_URL . '/dons?success=vente_reussie&montant=' . number_format($prix_vente, 2));
            exit;
            
        } catch (Exception $e) {
            if ($db && $db->inTransaction()) {
                $db->rollback();
            }
            
            error_log("Erreur lors de la vente: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/dons/' . $don_id . '/vendre?error=erreur_vente');
            exit;
        }
    }
    
    /**
     * API pour vérifier si un article est vendable
     */
    public static function checkVendable($don_id) {
        header('Content-Type: application/json');
        echo json_encode(self::checkVendabiliteInterne($don_id));
        exit;
    }
    
    /**
     * Vérifier si un don est vendable (logique interne)
     */
    private static function checkVendabiliteInterne($don_id) {
        try {
            $db = getDB();
            
            // Récupérer les détails du don
            $stmt = $db->prepare("SELECT * FROM dons WHERE id = ?");
            $stmt->execute([$don_id]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                return ['vendable' => false, 'raison' => 'Don inexistant'];
            }
            
            if ($don['vendu']) {
                return ['vendable' => false, 'raison' => 'Article déjà vendu'];
            }
            
            // Vérifier si l'article correspond à un besoin essentiel
            $stmt = $db->prepare("SELECT COUNT(*) FROM besoins 
                                WHERE nom = ? AND essentiel = TRUE");
            $stmt->execute([$don['type_don']]);
            $besoin_essentiel = $stmt->fetchColumn();
            
            if ($besoin_essentiel > 0) {
                return ['vendable' => false, 'raison' => 'Article essentiel - vente non permise'];
            }
            
            // Vérifier s'il y a encore des besoins non satisfaits pour ce type d'article
            $stmt = $db->prepare("
                SELECT b.id, b.nom, b.nombre, v.nom as ville_nom,
                       COALESCE(SUM(e.nombre_echangee), 0) as satisfait
                FROM besoins b 
                JOIN ville v ON b.id_ville = v.id
                LEFT JOIN echange e ON b.id = e.id_besoins
                WHERE b.nom = ?
                GROUP BY b.id, b.nom, b.nombre, v.nom
                HAVING (b.nombre - satisfait) > 0
            ");
            $stmt->execute([$don['type_don']]);
            $besoins_restants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($besoins_restants) > 0) {
                return [
                    'vendable' => false, 
                    'raison' => 'Vente non permise - Article encore demandé par ' . count($besoins_restants) . ' ville(s)',
                    'details' => $besoins_restants
                ];
            }
            
            // L'article peut être vendu
            return [
                'vendable' => true, 
                'raison' => 'Article vendable - Plus de demande en cours',
                'taux_diminution' => self::getTauxDiminution()
            ];
            
        } catch (Exception $e) {
            error_log("Erreur vérification vendabilité: " . $e->getMessage());
            return ['vendable' => false, 'raison' => 'Erreur système'];
        }
    }
    
    /**
     * Récupérer le taux de diminution configuré
     */
    private static function getTauxDiminution() {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");  
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (float)$result['valeur'] : 10; // 10% par défaut
            
        } catch (Exception $e) {
            error_log("Erreur récupération taux: " . $e->getMessage());
            return 10; // Valeur par défaut
        }
    }
    
    /**
     * API pour calculer le prix de vente
     */
    public static function calculatePriceApi($don_id) {
        header('Content-Type: application/json');
        
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT prix_original FROM dons WHERE id = ?");
            $stmt->execute([$don_id]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                echo json_encode(['success' => false, 'error' => 'Don non trouvé']);
                exit;
            }
            
            $taux = self::getTauxDiminution();
            $prix_original = $don['prix_original'] ?? 0;
            $prix_vente = $prix_original * (1 - $taux / 100);
            
            echo json_encode([
                'success' => true,
                'prix_original' => $prix_original,
                'prix_vente' => $prix_vente,
                'taux_diminution' => $taux,
                'economie' => $prix_original - $prix_vente
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        
        exit;
    }
}
?>