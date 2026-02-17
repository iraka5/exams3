<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Achats - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 999px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 1400px; margin: 20px auto; padding: 0 20px; }
        .filter { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .filter form { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .filter select, .filter button { padding: 10px 15px; border: 1px solid #ddd; border-radius: 999px; }
        .filter button { background: #3498db; color: white; border: none; cursor: pointer; }
        .filter button:hover { background: #2980b9; }
        .btn-add { background: #27ae60; color: white; padding: 12px 20px; text-decoration: none; border-radius: 999px; display: inline-block; margin-bottom: 20px; }
        .btn-add:hover { background: #229954; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: relative; }
        .stat-info { display: flex; flex-direction: column; align-items: center; }
        .stat-number { font-size: 28px; font-weight: bold; margin: 0; }
        .stat-label { color: #7f8c8d; font-size: 14px; }
        .stat-trend { font-size: 12px; }
        .trend-up { color: #27ae60; }
        .trend-down { color: #e74c3c; }
        .icon-besoins { position: absolute; top: 15px; right: 15px; font-size: 32px; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background: #3498db; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f8f9fa; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .badge-nature { background: #e8f5e8; color: #2e7d32; }
        .badge-materiaux { background: #fff3e0; color: #ef6c00; }
        .no-data { text-align: center; padding: 40px; color: #7f8c8d; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 12px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>💰 Gestion des Achats</h1>
        <p>Suivi des achats réalisés avec les dons en argent</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">🏠 Accueil</a>
        <a href="/exams3-main/exams3/achats" class="active">📝 Achats</a>
        <a href="/exams3-main/exams3/achats/create">➕ Nouvel Achat</a>
        <a href="/exams3-main/exams3/achats/recapitulatif">📊 Récapitulatif</a>
        <a href="/exams3-main/exams3/besoins">📋 Besoins</a>
        <a href="/exams3-main/exams3/dons">🎁 Dons</a>
        <a href="/exams3-main/exams3/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </nav>

    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <a href="/exams3-main/exams3/achats/create" class="btn-add">➕ Nouvel Achat</a>

        <div class="filter">
            <form method="GET">
                <label for="ville_id">Filtrer par ville :</label>
                <select name="ville_id" id="ville_id">
                    <option value="">-- Toutes les villes --</option>
                    <?php if (isset($villes)): ?>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id'] ?>" <?= (isset($_GET['ville_id']) && $_GET['ville_id'] == $ville['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville['region_nom'] . ' - ' . $ville['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit">🔍 Filtrer</button>
                <a href="/exams3-main/exams3/achats" style="margin-left: 10px; color: #3498db; text-decoration: none;">🗑️ Réinitialiser</a>
            </form>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Besoins actifs</h3>
                    <div class="stat-number">150</div>
                    <div class="stat-trend trend-up">↑ +11%</div>
                </div>
                <div class="stat-icon icon-besoins">📋</div>
            </div>
        </div>

        <?php if (empty($achats ?? [])): ?>
            <div class="no-data">
                <h3>Aucun achat trouvé</h3>
                <p>Il n'y a pas encore d'achats enregistrés pour les critères sélectionnés.</p>
                <a href="/exams3-main/exams3/achats/create" class="btn-add">Créer le premier achat</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($achats)): ?>
                        <?php foreach ($achats as $achat): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($achat['created_at'])) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($achat['ville_nom']) ?></strong><br>
                                    <small><?= htmlspecialchars($achat['region_nom']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($achat['besoin_nom']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $achat['type_besoin'] ?>">
                                        <?= ucfirst($achat['type_besoin']) ?>
                                    </span>
                                </td>
                                <td><?= number_format($achat['quantite'], 2, ',', ' ') ?></td>
                                <td><?= number_format($achat['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                <td><strong><?= number_format($achat['montant_total'], 0, ',', ' ') ?> Ar</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>