<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard V2 - BNGRC</title>
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Inter, -apple-system, sans-serif; background: #f8fafc; }
        
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .header p { opacity: 0.9; font-size: 16px; }
        
        .nav {
            background: white;
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 24px;
        }
        
        .nav a {
            color: #6b7280;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .nav a:hover, .nav a.active { background: #eff6ff; color: #2563eb; }
        
        .container { max-width: 1400px; margin: 24px auto; padding: 0 24px; }
        
        .refresh-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .refresh-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .refresh-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid var(--accent-color);
        }
        
        .stat-card.besoins { --accent-color: #ef4444; }
        .stat-card.satisfaits { --accent-color: #10b981; }
        .stat-card.dons { --accent-color: #3b82f6; }
        .stat-card.fonds { --accent-color: #f59e0b; }
        .stat-card.taux { --accent-color: #8b5cf6; }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 8px;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .chart-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .chart-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table-header {
            background: #f8fafc;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            text-align: left;
            padding: 16px 24px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .progress-bar {
            background: #f3f4f6;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 4px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            transition: width 0.3s ease;
        }
        
        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 40px;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .chart-section { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Dashboard V2 - Gestion Financière</h1>
        <p>Suivi en temps réel des achats, dons et besoins par ville</p>
    </div>
    
    <nav class="nav">
        <a href="/tableau-bord">🏠 Accueil</a>
        <a href="/dashboard-v2" class="active">📊 Dashboard V2</a>
        <a href="/achats">💰 Achats</a>
        <a href="/besoins">📋 Besoins</a>
        <a href="/dons">🎁 Dons</a>
        <a href="/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </nav>
    
    <div class="container">
        <div class="refresh-section">
            <div>
                <strong>Dernière mise à jour :</strong> <span id="lastUpdate">Chargement...</span>
            </div>
            <button class="refresh-btn" onclick="actualiserDashboard()">
                <span id="refreshIcon">🔄</span> Actualiser
            </button>
        </div>
        
        <!-- Alerte fonds faibles -->
        <div id="alerteFonds" class="alert" style="display: none;">
            <span>⚠️</span>
            <div>
                <strong>Attention !</strong> Les fonds disponibles sont inférieurs à 10 000 Ar. 
                Pensez à solliciter de nouveaux dons en argent.
            </div>
        </div>
        
        <!-- Statistiques principales -->
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card besoins">
                <div class="stat-value" id="besoinsTotal">-</div>
                <div class="stat-label">Besoins Totaux (Ar)</div>
            </div>
            
            <div class="stat-card satisfaits">
                <div class="stat-value" id="besoinsSatisfaits">-</div>
                <div class="stat-label">Besoins Satisfaits (Ar)</div>
            </div>
            
            <div class="stat-card dons">
                <div class="stat-value" id="donsRecus">-</div>
                <div class="stat-label">Dons Reçus (Ar)</div>
            </div>
            
            <div class="stat-card fonds">
                <div class="stat-value" id="fondsRestants">-</div>
                <div class="stat-label">Fonds Restants (Ar)</div>
            </div>
            
            <div class="stat-card taux">
                <div class="stat-value" id="tauxSatisfaction">-</div>
                <div class="stat-label">Taux de Satisfaction (%)</div>
            </div>
        </div>
        
        <!-- Tableaux détaillés -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">💰 Achats par Ville - Vue Détaillée</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Région</th>
                        <th>Ville</th>
                        <th>Nb Achats</th>
                        <th>Montant Achats</th>
                        <th>Besoins Totaux</th>
                        <th>Dons Reçus</th>
                        <th>Taux Satisfaction</th>
                    </tr>
                </thead>
                <tbody id="villesTableau">
                    <tr>
                        <td colspan="7" class="no-data">Chargement des données...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        let updateInterval;
        
        // Formater les nombres
        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(montant || 0));
        }
        
        // Actualiser le dashboard
        async function actualiserDashboard() {
            const refreshIcon = document.getElementById('refreshIcon');
            const refreshBtn = document.querySelector('.refresh-btn');
            
            // Animation de chargement
            refreshIcon.innerHTML = '<div class="loading"></div>';
            refreshBtn.disabled = true;
            
            try {
                // Récupérer les totaux globaux
                const response = await fetch('/api/dashboard-v2');
                const data = await response.json();
                
                // Mettre à jour les statistiques principales
                document.getElementById('besoinsTotal').textContent = formatMontant(data.totaux.besoins_total);
                document.getElementById('besoinsSatisfaits').textContent = formatMontant(data.totaux.besoins_satisfaits);
                document.getElementById('donsRecus').textContent = formatMontant(data.totaux.dons_recus);
                document.getElementById('fondsRestants').textContent = formatMontant(data.totaux.fonds_restants);
                document.getElementById('tauxSatisfaction').textContent = (data.totaux.taux_satisfaction || 0).toFixed(1);
                
                // Afficher/masquer l'alerte fonds faibles
                const alerteFonds = document.getElementById('alerteFonds');
                if (data.totaux.alerte_fonds) {
                    alerteFonds.style.display = 'flex';
                } else {
                    alerteFonds.style.display = 'none';
                }
                
                // Mettre à jour le tableau des villes
                const tbody = document.getElementById('villesTableau');
                if (data.villes && data.villes.length > 0) {
                    tbody.innerHTML = data.villes.map(ville => `
                        <tr>
                            <td><strong>${ville.region_nom}</strong></td>
                            <td>${ville.ville_nom}</td>
                            <td style="text-align: center;">
                                <span style="background: #e3f2fd; padding: 4px 8px; border-radius: 12px; font-weight: 600;">
                                    ${ville.nb_achats}
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #059669;">
                                ${formatMontant(ville.montant_achats)} Ar
                            </td>
                            <td style="text-align: right;">
                                ${formatMontant(ville.besoins_totaux)} Ar
                            </td>
                            <td style="text-align: right; color: #3b82f6;">
                                ${formatMontant(ville.dons_recus)} Ar
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="progress-bar" style="flex: 1;">
                                        <div class="progress-fill" style="width: ${Math.min(ville.taux_satisfaction, 100)}%"></div>
                                    </div>
                                    <span style="font-weight: 600; color: ${ville.taux_satisfaction >= 100 ? '#059669' : ville.taux_satisfaction >= 50 ? '#f59e0b' : '#ef4444'};">
                                        ${ville.taux_satisfaction}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="no-data">Aucune donnée disponible</td></tr>';
                }
                
                // Mettre à jour l'heure
                document.getElementById('lastUpdate').textContent = new Date().toLocaleString('fr-FR');
                
            } catch (error) {
                console.error('Erreur lors de l\'actualisation:', error);
                alert('Erreur lors de l\'actualisation des données');
            } finally {
                // Restaurer le bouton
                refreshIcon.innerHTML = '🔄';
                refreshBtn.disabled = false;
            }
        }
        
        // Actualisation automatique toutes les 30 secondes
        function demarrerActualisationAuto() {
            updateInterval = setInterval(actualiserDashboard, 30000);
        }
        
        function arreterActualisationAuto() {
            if (updateInterval) {
                clearInterval(updateInterval);
            }
        }
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            actualiserDashboard();
            demarrerActualisationAuto();
        });
        
        // Nettoyer l'intervalle à la fermeture de la page
        window.addEventListener('beforeunload', arreterActualisationAuto);
    </script>
</body>
</html>