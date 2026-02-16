<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif Financier - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #8e44ad, #9b59b6); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 999px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .summary-card { background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .summary-card:hover { transform: translateY(-5px); }
        .summary-card .number { font-size: 36px; font-weight: bold; margin-bottom: 5px; }
        .summary-card .label { color: #7f8c8d; font-size: 14px; }
        .summary-card.besoins .number { color: #e74c3c; }
        .summary-card.satisfaits .number { color: #27ae60; }
        .summary-card.dons-recus .number { color: #3498db; }
        .summary-card.dons-dispatches .number { color: #f39c12; }
        .summary-card.fonds-restants .number { color: #9b59b6; }
        .progress-section { background: white; padding: 25px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .progress-bar { background: #ecf0f1; height: 30px; border-radius: 15px; overflow: hidden; margin: 15px 0; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #27ae60, #2ecc71); transition: width 0.5s ease; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .btn-refresh { background: #3498db; color: white; padding: 15px 25px; border: none; border-radius: 999px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s; }
        .btn-refresh:hover { background: #2980b9; transform: translateY(-2px); }
        .btn-refresh:disabled { background: #95a5a6; cursor: not-allowed; transform: none; }
        .loading { display: none; color: #3498db; margin-left: 10px; }
        .timestamp { text-align: center; color: #7f8c8d; font-size: 14px; margin-top: 20px; }
        .details-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .detail-item { text-align: center; padding: 15px; border: 2px solid #ecf0f1; border-radius: 8px; }
        .detail-value { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .detail-label { color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Récapitulatif Financier</h1>
        <p>Vue d'ensemble des besoins, dons et achats</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">🏠 Accueil</a>
        <a href="/exams3-main/exams3/achats">📝 Achats</a>
        <a href="/exams3-main/exams3/achats/create">➕ Nouvel Achat</a>
        <a href="/exams3-main/exams3/achats/recapitulatif" class="active">📊 Récapitulatif</a>
        <a href="/exams3-main/exams3/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </nav>

    <div class="container">
        <?php if (isset($error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-bottom: 30px;">
            <button id="refreshBtn" class="btn-refresh" onclick="actualiserTotaux()">🔄 Actualiser les données</button>
            <span id="loading" class="loading">⏳ Actualisation en cours...</span>
        </div>

        <div class="summary" id="summaryCards">
            <div class="summary-card besoins">
                <div class="number" id="besoins-total"><?= number_format($totaux['besoins_total'], 0, ',', ' ') ?></div>
                <div class="label">Besoins totaux (Ar)</div>
            </div>
            <div class="summary-card satisfaits">
                <div class="number" id="besoins-satisfaits"><?= number_format($totaux['besoins_satisfaits'], 0, ',', ' ') ?></div>
                <div class="label">Besoins satisfaits (Ar)</div>
            </div>
            <div class="summary-card dons-recus">
                <div class="number" id="dons-recus"><?= number_format($totaux['dons_recus'], 0, ',', ' ') ?></div>
                <div class="label">Dons reçus (Ar)</div>
            </div>
            <div class="summary-card dons-dispatches">
                <div class="number" id="dons-dispatches"><?= number_format($totaux['dons_dispatches'], 0, ',', ' ') ?></div>
                <div class="label">Dons dispatchés (Ar)</div>
            </div>
            <div class="summary-card fonds-restants">
                <div class="number" id="fonds-restants"><?= number_format($totaux['fonds_restants'], 0, ',', ' ') ?></div>
                <div class="label">Fonds restants (Ar)</div>
            </div>
        </div>

        <div class="progress-section">
            <h3>📈 Taux de satisfaction des besoins</h3>
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill" style="width: <?= min(100, $totaux['taux_satisfaction']) ?>%">
                    <span id="progress-text"><?= number_format($totaux['taux_satisfaction'], 1) ?>%</span>
                </div>
            </div>
            <p style="text-align: center; color: #7f8c8d; margin: 0;">
                <span id="satisfaction-details"><?= number_format($totaux['besoins_satisfaits'], 0, ',', ' ') ?> Ar satisfaits sur <?= number_format($totaux['besoins_total'], 0, ',', ' ') ?> Ar de besoins totaux</span>
            </p>
        </div>

        <div class="details-section">
            <h3>💰 Détails financiers</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-value" style="color: #e74c3c;" id="detail-besoins"><?= number_format($totaux['besoins_total'], 0, ',', ' ') ?> Ar</div>
                    <div class="detail-label">Valeur totale des besoins identifiés</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value" style="color: #27ae60;" id="detail-satisfaits"><?= number_format($totaux['besoins_satisfaits'], 0, ',', ' ') ?> Ar</div>
                    <div class="detail-label">Montant des achats effectués</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value" style="color: #3498db;" id="detail-recus"><?= number_format($totaux['dons_recus'], 0, ',', ' ') ?> Ar</div>
                    <div class="detail-label">Total des dons en argent reçus</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value" style="color: #f39c12;" id="detail-dispatches"><?= number_format($totaux['dons_dispatches'], 0, ',', ' ') ?> Ar</div>
                    <div class="detail-label">Montant des dons utilisés</div>
                </div>
            </div>
        </div>

        <div class="timestamp" id="timestamp">
            Dernière mise à jour : <?= date('d/m/Y à H:i:s') ?>
        </div>
    </div>

    <script>
        function actualiserTotaux() {
            const refreshBtn = document.getElementById('refreshBtn');
            const loading = document.getElementById('loading');
            
            refreshBtn.disabled = true;
            loading.style.display = 'inline';
            
            fetch('/exams3-main/exams3/api/achats/totaux')
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des cartes résumé
                    document.getElementById('besoins-total').textContent = new Intl.NumberFormat('fr-FR').format(data.besoins_total);
                    document.getElementById('besoins-satisfaits').textContent = new Intl.NumberFormat('fr-FR').format(data.besoins_satisfaits);
                    document.getElementById('dons-recus').textContent = new Intl.NumberFormat('fr-FR').format(data.dons_recus);
                    document.getElementById('dons-dispatches').textContent = new Intl.NumberFormat('fr-FR').format(data.dons_dispatches);
                    document.getElementById('fonds-restants').textContent = new Intl.NumberFormat('fr-FR').format(data.fonds_restants);
                    
                    // Mise à jour de la barre de progression
                    const progressFill = document.getElementById('progress-fill');
                    const progressText = document.getElementById('progress-text');
                    const satisfactionDetails = document.getElementById('satisfaction-details');
                    
                    const taux = Math.min(100, data.taux_satisfaction);
                    progressFill.style.width = taux + '%';
                    progressText.textContent = taux.toFixed(1) + '%';
                    satisfactionDetails.textContent = `${new Intl.NumberFormat('fr-FR').format(data.besoins_satisfaits)} Ar satisfaits sur ${new Intl.NumberFormat('fr-FR').format(data.besoins_total)} Ar de besoins totaux`;
                    
                    // Mise à jour des détails
                    document.getElementById('detail-besoins').textContent = new Intl.NumberFormat('fr-FR').format(data.besoins_total) + ' Ar';
                    document.getElementById('detail-satisfaits').textContent = new Intl.NumberFormat('fr-FR').format(data.besoins_satisfaits) + ' Ar';
                    document.getElementById('detail-recus').textContent = new Intl.NumberFormat('fr-FR').format(data.dons_recus) + ' Ar';
                    document.getElementById('detail-dispatches').textContent = new Intl.NumberFormat('fr-FR').format(data.dons_dispatches) + ' Ar';
                    
                    // Mise à jour du timestamp
                    document.getElementById('timestamp').textContent = 'Dernière mise à jour : ' + new Date().toLocaleString('fr-FR');
                })
                .catch(error => {
                    console.error('Erreur lors de l\'actualisation:', error);
                    alert('Erreur lors de l\'actualisation des données');
                })
                .finally(() => {
                    refreshBtn.disabled = false;
                    loading.style.display = 'none';
                });
        }
    </script>
</body>
</html>