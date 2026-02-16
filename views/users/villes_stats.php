<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques par Ville - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 4px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 1400px; margin: 20px auto; padding: 0 20px; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .summary-card { background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .summary-card .number { font-size: 36px; font-weight: bold; margin-bottom: 5px; }
        .summary-card .label { color: #7f8c8d; font-size: 14px; }
        .summary-card.besoins .number { color: #e74c3c; }
        .summary-card.dons .number { color: #27ae60; }
        .summary-card.villes .number { color: #3498db; }
        .summary-card.couverture .number { color: #f39c12; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; }
        .ville-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        .ville-header { padding: 20px; background: linear-gradient(135deg, #34495e, #2c3e50); color: white; }
        .ville-header h3 { margin: 0; font-size: 18px; }
        .ville-header .region { font-size: 12px; opacity: 0.9; margin-top: 5px; }
        .ville-stats { padding: 20px; }
        .stat-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px; }
        .stat-label { font-weight: 500; color: #2c3e50; }
        .stat-value { font-weight: bold; font-size: 16px; }
        .stat-besoins { color: #e74c3c; }
        .stat-dons { color: #27ae60; }
        .progress-bar { background: #ecf0f1; height: 20px; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #27ae60, #2ecc71); transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; }
        .status { padding: 8px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-align: center; margin-top: 15px; }
        .status-excellent { background: #d5f4e6; color: #27ae60; }
        .status-bon { background: #fef9e7; color: #f39c12; }
        .status-insuffisant { background: #ffeaa7; color: #e67e22; }
        .status-critique { background: #ffebee; color: #e74c3c; }
        .back-btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; }
        .back-btn:hover { background: #2980b9; }
        .legend { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .legend h3 { margin: 0 0 15px 0; color: #2c3e50; }
        .legend-item { display: inline-block; margin: 5px 15px 5px 0; font-size: 14px; }
        .legend-dot { display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Statistiques par Ville</h1>
        <p>Tableau de bord des besoins et dons par ville</p>
    </div>

    <div class="nav">
        <a href="/exams3-main/exams3/user/dashboard">🏠 Accueil</a>
        <a href="/exams3-main/exams3/user/besoins">📋 Besoins</a>
        <a href="/exams3-main/exams3/user/dons">🎁 Faire un Don</a>
        <a href="/exams3-main/exams3/user/villes" class="active">📊 Statistiques Villes</a>
        <a href="/exams3-main/exams3/user/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </div>

    <div class="container">
        <a href="/exams3-main/exams3/user/dashboard" class="back-btn">← Retour au tableau de bord</a>

        <?php
        $totalVilles = count($villes);
        $totalBesoins = array_sum(array_column($villes, 'nb_besoins'));
        $totalDons = array_sum(array_column($villes, 'nb_dons'));
        $totalQuantiteBesoins = array_sum(array_column($villes, 'total_besoins'));
        $totalQuantiteDons = array_sum(array_column($villes, 'total_dons'));
        $couverture = $totalQuantiteBesoins > 0 ? round(($totalQuantiteDons / $totalQuantiteBesoins) * 100, 1) : 0;
        ?>

        <div class="summary">
            <div class="summary-card villes">
                <div class="number"><?= $totalVilles ?></div>
                <div class="label">Villes Suivies</div>
            </div>
            <div class="summary-card besoins">
                <div class="number"><?= $totalBesoins ?></div>
                <div class="label">Besoins Enregistrés</div>
            </div>
            <div class="summary-card dons">
                <div class="number"><?= $totalDons ?></div>
                <div class="label">Dons Reçus</div>
            </div>
            <div class="summary-card couverture">
                <div class="number"><?= $couverture ?>%</div>
                <div class="label">Taux de Couverture Global</div>
            </div>
        </div>

        <div class="legend">
            <h3>🎯 Légende du Taux de Couverture</h3>
            <div class="legend-item">
                <span class="legend-dot" style="background: #27ae60;"></span>
                Excellent (≥80%)
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #f39c12;"></span>
                Bon (60-79%)
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #e67e22;"></span>
                Insuffisant (30-59%)
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #e74c3c;"></span>
                Critique (<30%)
            </div>
        </div>

        <div class="cards-grid">
            <?php foreach ($villes as $ville): ?>
                <?php
                $tauxCouverture = $ville['total_besoins'] > 0 ? 
                    round(($ville['total_dons'] / $ville['total_besoins']) * 100, 1) : 0;
                
                $statusClass = 'status-critique';
                $statusText = 'Critique';
                if ($tauxCouverture >= 80) {
                    $statusClass = 'status-excellent';
                    $statusText = 'Excellent';
                } elseif ($tauxCouverture >= 60) {
                    $statusClass = 'status-bon';
                    $statusText = 'Bon';
                } elseif ($tauxCouverture >= 30) {
                    $statusClass = 'status-insuffisant';
                    $statusText = 'Insuffisant';
                }
                ?>
                <div class="ville-card">
                    <div class="ville-header">
                        <h3>🏙️ <?= htmlspecialchars($ville['ville_nom']) ?></h3>
                        <div class="region">📍 Région: <?= htmlspecialchars($ville['region_nom']) ?></div>
                    </div>
                    <div class="ville-stats">
                        <div class="stat-row">
                            <span class="stat-label">📋 Besoins enregistrés</span>
                            <span class="stat-value stat-besoins"><?= $ville['nb_besoins'] ?></span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">🎁 Dons reçus</span>
                            <span class="stat-value stat-dons"><?= $ville['nb_dons'] ?></span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">📊 Quantité nécessaire</span>
                            <span class="stat-value stat-besoins">
                                <?= number_format($ville['total_besoins'], 0, ',', ' ') ?>
                            </span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">✅ Quantité reçue</span>
                            <span class="stat-value stat-dons">
                                <?= number_format($ville['total_dons'], 0, ',', ' ') ?>
                            </span>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <small style="color: #7f8c8d;">Taux de couverture</small>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min($tauxCouverture, 100) ?>%;">
                                    <?= $tauxCouverture ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="status <?= $statusClass ?>">
                            <?= $statusText ?> - <?= $tauxCouverture ?>% couvert
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="background: white; padding: 25px; border-radius: 12px; margin-top: 30px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #2c3e50; margin: 0 0 15px 0;">💡 Comment aider ?</h3>
            <p style="color: #7f8c8d; margin: 0 0 20px 0;">
                Identifiez les villes avec un taux de couverture faible et concentrez vos dons sur ces zones prioritaires.
            </p>
            <a href="/exams3-main/exams3/user/dons" class="back-btn" style="background: #27ae60;">
                ❤️ Faire un Don Ciblé
            </a>
            <a href="/exams3-main/exams3/user/besoins" class="back-btn">
                📋 Voir les Besoins Détaillés
            </a>
        </div>
    </div>
</body>
</html>