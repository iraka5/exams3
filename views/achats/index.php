<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Achats - BNGRC</title>
    
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
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
        <a href="/exams3-main/exams3/logout" style="margin-left: auto;"> Sortir</a>
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