<?php
require_once __DIR__ . '/../config/config.php';

class AchatController {
    
    // Afficher le formulaire de création d'achat
    public static function create() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            header('Location: /exams3-main/exams3/login');
            exit;
        }
        
        $db = getDB();
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        
        // Pour chaque ville, récupérer les besoins
        foreach ($villes as &$ville) {
            $stmt = $db->prepare("SELECT id, description, prix_unitaire, nombre FROM besoins WHERE id_ville = ?");
            $stmt->execute([$ville['id']]);
            $ville['besoins'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include __DIR__ . '/../views/users/achats_form.php';
    }
    
    // Enregistrer un achat
    public static function store() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            header('Location: /exams3-main/exams3/login');
            exit;
        }
        
        $id_besoin = $_POST['id_besoin'] ?? 0;
        $quantite = floatval($_POST['quantite'] ?? 0);
        $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
        
        if ($id_besoin && $quantite > 0 && $prix_unitaire > 0) {
            try {
                $db = getDB();
                $montant = $quantite * $prix_unitaire;
                
                // Vérifier que le besoin existe
                $check = $db->prepare("SELECT id FROM besoins WHERE id = ?");
                $check->execute([$id_besoin]);
                if (!$check->fetch()) {
                    header('Location: /exams3-main/exams3/achats/create?error=besoin_invalid');
                    exit;
                }
                
                // Insérer l'achat
                $stmt = $db->prepare("INSERT INTO achats (id_besoin, quantite, prix_unitaire, montant, date_achat, statut) VALUES (?, ?, ?, ?, NOW(), 'En cours')");
                $stmt->execute([$id_besoin, $quantite, $prix_unitaire, $montant]);
                
                header('Location: /exams3-main/exams3/achats/liste?success=created');
                exit;
                
            } catch (PDOException $e) {
                error_log("Erreur achat: " . $e->getMessage());
                header('Location: /exams3-main/exams3/achats/create?error=db_error');
                exit;
            }
        }
        
        header('Location: /exams3-main/exams3/achats/create?error=invalid');
        exit;
    }
    
    // Liste des achats
    public static function liste() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            header('Location: /exams3-main/exams3/login');
            exit;
        }
        
        $db = getDB();
        $villes = $db->query("SELECT * FROM ville ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
        
        // Construire la requête avec filtres
        $sql = "SELECT a.*, v.nom as ville_nom, b.description as besoin_description 
                FROM achats a
                JOIN besoins b ON a.id_besoin = b.id
                JOIN ville v ON b.id_ville = v.id
                WHERE 1=1";
        $params = [];
        
        if (isset($_GET['ville']) && !empty($_GET['ville'])) {
            $sql .= " AND v.id = ?";
            $params[] = $_GET['ville'];
        }
        
        if (isset($_GET['date_debut']) && !empty($_GET['date_debut'])) {
            $sql .= " AND DATE(a.date_achat) >= ?";
            $params[] = $_GET['date_debut'];
        }
        
        if (isset($_GET['date_fin']) && !empty($_GET['date_fin'])) {
            $sql .= " AND DATE(a.date_achat) <= ?";
            $params[] = $_GET['date_fin'];
        }
        
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include __DIR__ . '/../views/users/achats_liste.php';
    }
    
    // Récupérer les totaux pour le récapitulatif
    public static function getTotaux() {
        try {
            $db = getDB();
            
            // Total des besoins
            $stmt = $db->query("SELECT SUM(nombre * prix_unitaire) as total FROM besoins WHERE prix_unitaire IS NOT NULL");
            $besoins_total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total des achats
            $stmt = $db->query("SELECT SUM(montant) as total FROM achats WHERE statut = 'Livré'");
            $besoins_satisfaits = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Total des dons
            $stmt = $db->query("SELECT SUM(montant) as total FROM dons");
            $dons_recus = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Dons dispatchés (achats effectués)
            $stmt = $db->query("SELECT SUM(montant) as total FROM achats");
            $dons_dispatches = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            // Fonds restants
            $fonds_restants = $dons_recus - $dons_dispatches;
            
            // Taux de satisfaction
            $taux_satisfaction = $besoins_total > 0 ? round(($besoins_satisfaits / $besoins_total) * 100, 1) : 0;
            
            return [
                'besoins_total' => $besoins_total,
                'besoins_satisfaits' => $besoins_satisfaits,
                'dons_recus' => $dons_recus,
                'dons_dispatches' => $dons_dispatches,
                'fonds_restants' => $fonds_restants,
                'taux_satisfaction' => $taux_satisfaction
            ];
            
        } catch (PDOException $e) {
            error_log("Erreur récapitulatif: " . $e->getMessage());
            return [
                'besoins_total' => 0,
                'besoins_satisfaits' => 0,
                'dons_recus' => 0,
                'dons_dispatches' => 0,
                'fonds_restants' => 0,
                'taux_satisfaction' => 0
            ];
        }
    }
    
    // Page récapitulatif
    public static function recapitulatif() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
            header('Location: /exams3-main/exams3/login');
            exit;
        }
        
        $totaux = self::getTotaux();
        include __DIR__ . '/../views/users/recapitulatif.php';
    }
    
    // API pour actualiser les totaux (Ajax)
    public static function apiTotaux() {
        header('Content-Type: application/json');
        echo json_encode(self::getTotaux());
        exit;
    }
}
?>