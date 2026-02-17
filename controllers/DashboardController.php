<?php
require_once __DIR__ . "/../models/Achat.php";

class DashboardController
{
    /**
     * Afficher le Dashboard V2
     */
    public static function dashboardV2()
    {
        Flight::render("dashboard_v2");
    }

    /**
     * API pour récupérer les données du Dashboard V2 (AJAX)
     */
    public static function apiDashboardV2()
    {
        header('Content-Type: application/json');
        
        try {
            // Récupérer les totaux globaux
            $totaux = Achat::calculerTotauxGlobaux();
            
            // Récupérer les statistiques détaillées par ville
            $villes = Achat::getStatistiquesDetailleesParVille();
            
            $response = [
                'success' => true,
                'totaux' => $totaux,
                'villes' => $villes,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API pour vérifier les fonds avant un achat
     */
    public static function apiVerifierFonds()
    {
        header('Content-Type: application/json');
        
        $montant = floatval($_GET['montant'] ?? 0);
        
        if ($montant <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'Montant invalide'
            ]);
            return;
        }
        
        try {
            $fondsDisponibles = Achat::verifierFondsDisponibles($montant);
            $totaux = Achat::calculerTotauxGlobaux();
            
            echo json_encode([
                'success' => true,
                'fonds_suffisants' => $fondsDisponibles,
                'fonds_restants' => $totaux['fonds_restants'],
                'montant_demande' => $montant
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
?>