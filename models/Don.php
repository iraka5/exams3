<?php
// models/Don.php
require_once __DIR__ . '/../config/config.php';

class Don {
    
    // Récupérer tous les dons
    public static function all() {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT d.*, v.nom as ville_nom FROM dons d JOIN ville v ON d.id_ville = v.id ORDER BY d.id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer un don par ID
    public static function find($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT d.*, v.nom as ville_nom FROM dons d JOIN ville v ON d.id_ville = v.id WHERE d.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Créer un don
    public static function create($nom_donneur, $type_don, $nombre_don, $id_ville) {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO dons (nom_donneur, type_don, nombre_don, id_ville, statut) VALUES (?, ?, ?, ?, 'disponible')");
        return $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville]);
    }
    
    // Mettre à jour un don
    public static function update($id, $nom_donneur, $type_don, $nombre_don, $id_ville) {
        $pdo = getDB();
        $stmt = $pdo->prepare("UPDATE dons SET nom_donneur = ?, type_don = ?, nombre_don = ?, id_ville = ? WHERE id = ?");
        return $stmt->execute([$nom_donneur, $type_don, $nombre_don, $id_ville, $id]);
    }
    
    // Supprimer un don
    public static function delete($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM dons WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========== MÉTHODES POUR LA VENTE ==========
    
    /**
     * Vérifie si un don peut être vendu
     */
    public static function isVendable($id_don) {
        try {
            $pdo = getDB();
            
            // Récupérer les informations du don
            $stmt = $pdo->prepare("SELECT * FROM dons WHERE id = ?");
            $stmt->execute([$id_don]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                return [
                    'vendable' => false, 
                    'raison' => 'Don non trouvé'
                ];
            }
            
            // Vérifier si le don est déjà vendu
            if ($don['statut'] === 'vendu') {
                return [
                    'vendable' => false,
                    'raison' => 'Ce don a déjà été vendu'
                ];
            }
            
            // Vérifier si c'est un article essentiel (type 'nature' ou 'materiaux')
            $est_essentiel = in_array($don['type_don'], ['nature', 'materiaux']);
            
            // Vérifier s'il existe encore des besoins pour cette ville et ce type
            $stmt = $pdo->prepare("
                SELECT COALESCE(SUM(nombre), 0) as total_besoin 
                FROM besoins 
                WHERE id_ville = ? AND type_besoin = ?
            ");
            $stmt->execute([$don['id_ville'], $don['type_don']]);
            $besoin = $stmt->fetch(PDO::FETCH_ASSOC);
            $besoin_restant = $besoin['total_besoin'] ?? 0;
            
            // Règles de vente
            if ($est_essentiel && $besoin_restant > 0) {
                return [
                    'vendable' => false,
                    'raison' => 'Article essentiel encore nécessaire',
                    'besoin_restant' => $besoin_restant
                ];
            }
            
            // Si plus de besoin OU article non essentiel (argent)
            return [
                'vendable' => true,
                'raison' => 'Vente autorisée',
                'besoin_restant' => $besoin_restant
            ];
            
        } catch (PDOException $e) {
            error_log("Erreur isVendable: " . $e->getMessage());
            return [
                'vendable' => false,
                'raison' => 'Erreur technique'
            ];
        }
    }
    
    /**
     * Calcule le prix réduit d'un don
     */
    public static function calculPrixReduit($id_don, $taux_diminution = null) {
        try {
            $pdo = getDB();
            
            // Récupérer le don
            $stmt = $pdo->prepare("SELECT * FROM dons WHERE id = ?");
            $stmt->execute([$id_don]);
            $don = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$don) {
                return null;
            }
            
            // Récupérer le taux depuis les paramètres si non fourni
            if ($taux_diminution === null) {
                $stmt = $pdo->query("SELECT valeur FROM parametres WHERE cle = 'taux_diminution_vente'");
                $param = $stmt->fetch(PDO::FETCH_ASSOC);
                $taux_diminution = $param ? floatval($param['valeur']) : 10;
            }
            
            // Définir un prix de base selon le type
            $prix_base = 0;
            switch($don['type_don']) {
                case 'nature':
                    $prix_base = $don['nombre_don'] * 1000; // 1000 Ar par unité
                    break;
                case 'materiaux':
                    $prix_base = $don['nombre_don'] * 5000; // 5000 Ar par unité
                    break;
                case 'argent':
                    $prix_base = $don['nombre_don']; // C'est déjà de l'argent
                    break;
            }
            
            $prix_reduit = $prix_base * (1 - $taux_diminution / 100);
            
            return [
                'prix_original' => $prix_base,
                'prix_reduit' => $prix_reduit,
                'taux' => $taux_diminution,
                'type' => $don['type_don'],
                'quantite' => $don['nombre_don'],
                'id_don' => $don['id'],
                'nom_donneur' => $don['nom_donneur'],
                'ville_id' => $don['id_ville']
            ];
            
        } catch (PDOException $e) {
            error_log("Erreur calculPrixReduit: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Vendre un don
     */
    public static function vendre($id_don, $taux_diminution = null) {
        try {
            $pdo = getDB();
            $pdo->beginTransaction();
            
            // Vérifier si le don peut être vendu
            $check = self::isVendable($id_don);
            if (!$check['vendable']) {
                return false;
            }
            
            // Calculer le prix
            $prix_info = self::calculPrixReduit($id_don, $taux_diminution);
            if (!$prix_info) {
                return false;
            }
            
            // Récupérer les infos du don
            $don = self::find($id_don);
            
            // Récupérer le nom de la ville
            $stmt = $pdo->prepare("SELECT nom FROM ville WHERE id = ?");
            $stmt->execute([$don['id_ville']]);
            $ville = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Insérer dans l'historique des ventes
            $stmt = $pdo->prepare("
                INSERT INTO ventes 
                (id_don, prix_original, prix_vente, taux_applique, date_vente, type_don, ville_nom, ville_id, quantite, nom_donneur) 
                VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_don,
                $prix_info['prix_original'],
                $prix_info['prix_reduit'],
                $prix_info['taux'],
                $don['type_don'],
                $ville['nom'],
                $don['id_ville'],
                $don['nombre_don'],
                $don['nom_donneur']
            ]);
            
            // Marquer le don comme vendu
            $stmt = $pdo->prepare("UPDATE dons SET statut = 'vendu' WHERE id = ?");
            $stmt->execute([$id_don]);
            
            $pdo->commit();
            return true;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Erreur vendre: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer les statistiques des ventes
     */
    public static function getStatsVentes() {
        try {
            $pdo = getDB();
            
            $stats = [];
            
            // Total des ventes
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM ventes");
            $stats['total_ventes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            if ($stats['total_ventes'] > 0) {
                // Valeur totale des ventes
                $stmt = $pdo->query("SELECT SUM(prix_vente) as total FROM ventes");
                $stats['valeur_vente_totale'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                // Valeur originale totale
                $stmt = $pdo->query("SELECT SUM(prix_original) as total FROM ventes");
                $stats['valeur_originale_totale'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                // Taux moyen appliqué
                $stmt = $pdo->query("SELECT AVG(taux_applique) as moyen FROM ventes");
                $stats['taux_moyen_realise'] = round($stmt->fetch(PDO::FETCH_ASSOC)['moyen'], 1);
            } else {
                $stats['valeur_vente_totale'] = 0;
                $stats['valeur_originale_totale'] = 0;
                $stats['taux_moyen_realise'] = 0;
            }
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Erreur getStatsVentes: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Récupérer les dernières ventes
     */
    public static function getDernieresVentes($limite = 10) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM ventes ORDER BY date_vente DESC LIMIT ?");
            $stmt->execute([$limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getDernieresVentes: " . $e->getMessage());
            return [];
        }
    }
}
?>