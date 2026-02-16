<?php

class AchatController
{
    private $pdo;
    
    public function __construct()
    {
        require_once __DIR__ . '/../config/config.php';
        $this->pdo = getDB();
    }
    
    /**
     * Affiche la liste des achats avec filtre par ville
     */
    public function index()
    {
        try {
            $ville_filter = $_GET['ville_id'] ?? '';
            
            // Récupération des villes pour le filtre
            $stmt_villes = $this->pdo->query("
                SELECT v.id, v.nom, r.nom as region_nom 
                FROM ville v 
                INNER JOIN regions r ON v.id_regions = r.id 
                ORDER BY r.nom, v.nom
            ");
            $villes = $stmt_villes->fetchAll(PDO::FETCH_ASSOC);
            
            // Construction de la requête des achats avec filtre
            $sql = "
                SELECT a.*, b.nom as besoin_nom, b.prix_unitaire, b.type_besoin,
                       v.nom as ville_nom, r.nom as region_nom
                FROM achats a
                INNER JOIN besoins b ON a.id_besoin = b.id  
                INNER JOIN ville v ON a.id_ville = v.id
                INNER JOIN regions r ON v.id_regions = r.id
            ";
            
            $params = [];
            if (!empty($ville_filter)) {
                $sql .= " WHERE a.id_ville = :ville_id";
                $params['ville_id'] = $ville_filter;
            }
            
            $sql .= " ORDER BY a.created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculs des totaux pour la ville filtrée
            $totaux = $this->calculerTotaux($ville_filter);
            
            require_once __DIR__ . '/../views/achats/index.php';
            
        } catch (Exception $e) {
            // En cas d'erreur, initialiser les variables vides
            $villes = [];
            $achats = [];
            $totaux = [
                'besoins_total' => 0,
                'besoins_satisfaits' => 0,
                'dons_recus' => 0,
                'dons_dispatches' => 0,
                'fonds_restants' => 0,
                'taux_satisfaction' => 0
            ];
            $error = 'Erreur lors du chargement des achats: ' . $e->getMessage();
            require_once __DIR__ . '/../views/achats/index.php';
        }
    }
    
    /**
     * Affiche le formulaire d'achat
     */
    public function create()
    {
        try {
            // Récupération des villes
            $stmt_villes = $this->pdo->query("
                SELECT v.id, v.nom, r.nom as region_nom 
                FROM ville v 
                INNER JOIN regions r ON v.id_regions = r.id 
                ORDER BY r.nom, v.nom
            ");
            $villes = $stmt_villes->fetchAll(PDO::FETCH_ASSOC);
            
            // Récupération des besoins avec prix
            $stmt_besoins = $this->pdo->query("
                SELECT b.*, v.nom as ville_nom 
                FROM besoins b 
                INNER JOIN ville v ON b.id_ville = v.id 
                WHERE b.type_besoin != 'argent'
                ORDER BY b.nom
            ");
            $besoins = $stmt_besoins->fetchAll(PDO::FETCH_ASSOC);
            
            require_once __DIR__ . '/../views/achats/create.php';
            
        } catch (Exception $e) {
            // En cas d'erreur, initialiser les variables vides
            $villes = [];
            $besoins = [];
            $error = 'Erreur lors du chargement du formulaire: ' . $e->getMessage();
            require_once __DIR__ . '/../views/achats/create.php';
        }
    }
    
    /**
     * Traite l'insertion d'un nouvel achat
     */
    public function store()
    {
        try {
            $id_besoin = $_POST['id_besoin'] ?? '';
            $quantite = $_POST['quantite'] ?? '';
            $id_ville = $_POST['id_ville'] ?? '';
            
            if (empty($id_besoin) || empty($quantite) || empty($id_ville)) {
                Flight::redirect('/achats/create?error=Tous les champs sont requis');
                return;
            }
            
            if ($quantite <= 0) {
                Flight::redirect('/achats/create?error=La quantité doit être positive');
                return;
            }
            
            // Récupération du prix unitaire
            $stmt = $this->pdo->prepare("SELECT prix_unitaire FROM besoins WHERE id = :id");
            $stmt->execute(['id' => $id_besoin]);
            $besoin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$besoin) {
                Flight::redirect('/achats/create?error=Besoin non trouvé');
                return;
            }
            
            // Calcul automatique du montant
            $montant_total = $quantite * $besoin['prix_unitaire'];
            
            // Vérification des fonds disponibles (dons en argent)
            $fonds_disponibles = $this->calculerFondsDisponibles($id_ville);
            if ($montant_total > $fonds_disponibles) {
                Flight::redirect('/achats/create?error=Fonds insuffisants. Disponible: ' . number_format($fonds_disponibles, 2) . ' Ar');
                return;
            }
            
            // Insertion de l'achat
            $stmt = $this->pdo->prepare("
                INSERT INTO achats (id_besoin, quantite, montant_total, id_ville) 
                VALUES (:id_besoin, :quantite, :montant_total, :id_ville)
            ");
            
            $result = $stmt->execute([
                'id_besoin' => $id_besoin,
                'quantite' => $quantite, 
                'montant_total' => $montant_total,
                'id_ville' => $id_ville
            ]);
            
            if ($result) {
                Flight::redirect('/achats?success=Achat enregistré avec succès');
            } else {
                Flight::redirect('/achats/create?error=Erreur lors de l\'enregistrement');
            }
            
        } catch (Exception $e) {
            Flight::redirect('/achats/create?error=Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * API pour récupérer les besoins d'une ville (AJAX)
     */
    public function getBesoinsByVille($id_ville)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, nom, prix_unitaire, type_besoin, nombre
                FROM besoins 
                WHERE id_ville = :id_ville AND type_besoin != 'argent'
                ORDER BY nom
            ");
            $stmt->execute(['id_ville' => $id_ville]);
            $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Flight::json($besoins);
            
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Page de récapitulation avec totaux
     */
    public function recapitulatif()
    {
        try {
            $totaux = $this->calculerTotauxGlobaux();
            require_once __DIR__ . '/../views/achats/recapitulatif.php';
            
        } catch (Exception $e) {
            // En cas d'erreur, initialiser les totaux à zéro
            $totaux = [
                'besoins_total' => 0,
                'besoins_satisfaits' => 0,
                'dons_recus' => 0,
                'dons_dispatches' => 0,
                'fonds_restants' => 0,
                'taux_satisfaction' => 0
            ];
            $error = 'Erreur lors du calcul des totaux: ' . $e->getMessage();
            require_once __DIR__ . '/../views/achats/recapitulatif.php';
        }
    }
    
    /**
     * API AJAX pour actualiser les totaux
     */
    public function actualiserTotaux()
    {
        try {
            $totaux = $this->calculerTotauxGlobaux();
            Flight::json($totaux);
            
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Calcule les totaux pour une ville ou globalement
     */
    private function calculerTotaux($ville_filter = null)
    {
        $where_clause = $ville_filter ? "WHERE v.id = :ville_id" : "";
        $params = $ville_filter ? ['ville_id' => $ville_filter] : [];
        
        // Besoins totaux en montant
        $stmt = $this->pdo->prepare("
            SELECT SUM(b.nombre * b.prix_unitaire) as montant_besoins_total
            FROM besoins b 
            INNER JOIN ville v ON b.id_ville = v.id 
            $where_clause
        ");
        $stmt->execute($params);
        $besoins_total = $stmt->fetchColumn() ?? 0;
        
        // Besoins satisfaits par achats
        $stmt = $this->pdo->prepare("
            SELECT SUM(a.montant_total) as montant_satisfaits
            FROM achats a 
            INNER JOIN ville v ON a.id_ville = v.id 
            $where_clause
        ");
        $stmt->execute($params);
        $besoins_satisfaits = $stmt->fetchColumn() ?? 0;
        
        // Dons reçus total
        $stmt = $this->pdo->prepare("
            SELECT SUM(d.nombre_don) as dons_recus
            FROM dons d 
            INNER JOIN ville v ON d.id_ville = v.id 
            WHERE d.type_don = 'Argent' $where_clause
        ");
        if ($ville_filter) {
            $stmt->execute($params);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT SUM(d.nombre_don) as dons_recus
                FROM dons d 
                WHERE d.type_don = 'Argent'
            ");
            $stmt->execute();
        }
        $dons_recus = $stmt->fetchColumn() ?? 0;
        
        // Dons dispatchés (utilisés pour achats)
        $dons_dispatches = $besoins_satisfaits;
        
        return [
            'besoins_total' => $besoins_total,
            'besoins_satisfaits' => $besoins_satisfaits,
            'dons_recus' => $dons_recus,
            'dons_dispatches' => $dons_dispatches,
            'fonds_restants' => $dons_recus - $dons_dispatches,
            'taux_satisfaction' => $besoins_total > 0 ? ($besoins_satisfaits / $besoins_total * 100) : 0
        ];
    }
    
    /**
     * Calcule les totaux globaux pour la récap
     */
    private function calculerTotauxGlobaux()
    {
        return $this->calculerTotaux();
    }
    
    /**
     * Calcule les fonds disponibles pour une ville
     */
    private function calculerFondsDisponibles($id_ville)
    {
        // Dons en argent reçus pour la ville
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(nombre_don), 0) as dons_argent
            FROM dons 
            WHERE id_ville = :id_ville AND type_don = 'Argent'
        ");
        $stmt->execute(['id_ville' => $id_ville]);
        $dons_argent = $stmt->fetchColumn() ?? 0;
        
        // Achats déjà effectués pour la ville  
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(montant_total), 0) as achats_total
            FROM achats 
            WHERE id_ville = :id_ville
        ");
        $stmt->execute(['id_ville' => $id_ville]);
        $achats_total = $stmt->fetchColumn() ?? 0;
        
        return $dons_argent - $achats_total;
    }
}