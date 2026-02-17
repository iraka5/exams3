<?php
require_once __DIR__ . "/../config/config.php";

class Achat
{
    public static function all()
    {
        $db = getDB();
        $sql = "SELECT achats.*, 
                       besoins.nom AS besoin_nom, 
                       besoins.prix_unitaire, 
                       besoins.type_besoin,
                       ville.nom AS ville_nom, 
                       regions.nom AS region_nom
                FROM achats
                JOIN besoins ON achats.id_besoin = besoins.id
                JOIN ville ON achats.id_ville = ville.id
                JOIN regions ON ville.id_regions = regions.id
                ORDER BY achats.created_at DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allByVille($id_ville)
    {
        $db = getDB();
        $sql = "SELECT achats.*, 
                       besoins.nom AS besoin_nom, 
                       besoins.prix_unitaire, 
                       besoins.type_besoin,
                       ville.nom AS ville_nom, 
                       regions.nom AS region_nom
                FROM achats
                JOIN besoins ON achats.id_besoin = besoins.id
                JOIN ville ON achats.id_ville = ville.id
                JOIN regions ON ville.id_regions = regions.id
                WHERE achats.id_ville = ?
                ORDER BY achats.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si les fonds sont suffisants pour un achat
     */
    public static function verifierFondsDisponibles($montant_achat)
    {
        $totaux = self::calculerTotauxGlobaux();
        return $totaux['fonds_restants'] >= $montant_achat;
    }
    
    /**
     * Créer un achat avec vérification automatique des fonds
     */
    public static function createAvecVerification($id_besoin, $quantite, $montant_total, $id_ville)
    {
        // Vérifier les fonds disponibles
        if (!self::verifierFondsDisponibles($montant_total)) {
            throw new Exception("Fonds insuffisants. Montant demandé: " . number_format($montant_total, 0, ',', ' ') . " Ar. Fonds disponibles: " . number_format(self::calculerTotauxGlobaux()['fonds_restants'], 0, ',', ' ') . " Ar.");
        }
        
        // Effectuer l'achat
        $db = getDB();
        $sql = "INSERT INTO achats(id_besoin, quantite, montant_total, id_ville) VALUES(?,?,?,?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id_besoin, $quantite, $montant_total, $id_ville]);
    }

    public static function find($id)
    {
        $db = getDB();
        $sql = "SELECT achats.*, 
                       besoins.nom AS besoin_nom, 
                       besoins.prix_unitaire, 
                       besoins.type_besoin,
                       ville.nom AS ville_nom, 
                       regions.nom AS region_nom
                FROM achats
                JOIN besoins ON achats.id_besoin = besoins.id
                JOIN ville ON achats.id_ville = ville.id
                JOIN regions ON ville.id_regions = regions.id
                WHERE achats.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTotalMontantByVille($id_ville = null)
    {
        $db = getDB();
        if ($id_ville) {
            $sql = "SELECT COALESCE(SUM(montant_total), 0) as total FROM achats WHERE id_ville = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_ville]);
        } else {
            $sql = "SELECT COALESCE(SUM(montant_total), 0) as total FROM achats";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchColumn();
    }

    public static function getCountByVille($id_ville = null)
    {
        $db = getDB();
        if ($id_ville) {
            $sql = "SELECT COUNT(*) as count FROM achats WHERE id_ville = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_ville]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM achats";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchColumn();
    }

    public static function getMontantAchatsByVille()
    {
        $db = getDB();
        $sql = "SELECT v.nom as ville_nom, 
                       r.nom as region_nom,
                       COALESCE(SUM(a.montant_total), 0) as montant_achats,
                       COUNT(a.id) as nb_achats
                FROM ville v
                JOIN regions r ON v.id_regions = r.id
                LEFT JOIN achats a ON v.id = a.id_ville
                GROUP BY v.id, v.nom, r.nom
                ORDER BY montant_achats DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $db = getDB();
        $sql = "DELETE FROM achats WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Calcule les totaux pour le récapitulatif avec gestion avancée des dispatches
     */
    public static function calculerTotauxGlobaux()
    {
        $db = getDB();
        
        // Besoins totaux en montant
        $sql_besoins = "SELECT COALESCE(SUM(nombre * prix_unitaire), 0) as total FROM besoins WHERE prix_unitaire IS NOT NULL";
        $besoins_total = $db->query($sql_besoins)->fetchColumn();
        
        // Besoins satisfaits (achats effectués)
        $sql_satisfaits = "SELECT COALESCE(SUM(montant_total), 0) as total FROM achats";
        $besoins_satisfaits = $db->query($sql_satisfaits)->fetchColumn();
        
        // Dons en argent reçus
        $sql_dons = "SELECT COALESCE(SUM(nombre_don), 0) as total FROM dons WHERE type_don LIKE '%argent%' OR type_don LIKE '%Argent%' OR type_don LIKE '%ARGENT%'";
        $dons_recus = $db->query($sql_dons)->fetchColumn();
        
        // Dons dispatchés = achats effectués
        $dons_dispatches = $besoins_satisfaits;
        
        // Calculs dérivés
        $fonds_restants = $dons_recus - $dons_dispatches;
        $taux_satisfaction = $besoins_total > 0 ? ($besoins_satisfaits / $besoins_total * 100) : 0;
        
        // Alerte si fonds faibles
        $alerte_fonds = $fonds_restants < 10000; // Alerte si moins de 10,000 Ar
        
        return [
            'besoins_total' => $besoins_total,
            'besoins_satisfaits' => $besoins_satisfaits,
            'dons_recus' => $dons_recus,
            'dons_dispatches' => $dons_dispatches,
            'fonds_restants' => $fonds_restants,
            'taux_satisfaction' => $taux_satisfaction,
            'alerte_fonds' => $alerte_fonds
        ];
    }
    
    /**
     * Obtenir les statistiques détaillées des achats par ville pour Dashboard V2
     */
    public static function getStatistiquesDetailleesParVille()
    {
        $db = getDB();
        $sql = "SELECT v.nom as ville_nom, 
                       r.nom as region_nom,
                       COALESCE(SUM(a.montant_total), 0) as montant_achats,
                       COUNT(a.id) as nb_achats,
                       COALESCE(SUM(b.nombre * b.prix_unitaire), 0) as besoins_totaux,
                       COALESCE(SUM(d.nombre_don), 0) as dons_recus,
                       ROUND(COALESCE(SUM(a.montant_total) / NULLIF(SUM(b.nombre * b.prix_unitaire), 0) * 100, 0), 1) as taux_satisfaction
                FROM ville v
                JOIN regions r ON v.id_regions = r.id
                LEFT JOIN achats a ON v.id = a.id_ville
                LEFT JOIN besoins b ON v.id = b.id_ville AND b.prix_unitaire IS NOT NULL
                LEFT JOIN dons d ON v.id = d.id_ville AND (d.type_don LIKE '%argent%' OR d.type_don LIKE '%Argent%')
                GROUP BY v.id, v.nom, r.nom
                ORDER BY montant_achats DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}