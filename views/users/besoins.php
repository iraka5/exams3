<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins des Sinistrés - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 4px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .filter { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .filter form { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .filter select, .filter button { padding: 10px 15px; border: 1px solid #ddd; border-radius: 4px; }
        .filter button { background: #e74c3c; color: white; border: none; cursor: pointer; }
        .filter button:hover { background: #c0392b; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-number { font-size: 28px; font-weight: bold; color: #e74c3c; }
        .stat-label { color: #7f8c8d; margin-top: 5px; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background: #e74c3c; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f8f9fa; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .badge-urgent { background: #ffebee; color: #c62828; }
        .badge-normal { background: #e8f5e8; color: #2e7d32; }
        .badge-faible { background: #fff3e0; color: #ef6c00; }
        .no-data { text-align: center; padding: 40px; color: #7f8c8d; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .back-btn { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; }
        .back-btn:hover { background: #229954; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Besoins des Sinistrés</h1>
        <p>Consultez les besoins urgents dans toutes les régions</p>
    </div>

    <div class="nav">
        <a href="/exams3-main/exams3/user/dashboard">🏠 Accueil</a>
        <a href="/exams3-main/exams3/user/besoins" class="active">📋 Besoins</a>
        <a href="/exams3-main/exams3/user/dons">🎁 Faire un Don</a>
        <a href="/exams3-main/exams3/user/villes">📊 Statistiques Villes</a>
        <a href="/exams3-main/exams3/user/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </div>

    <div class="container">
        <a href="/exams3-main/exams3/user/dashboard" class="back-btn">← Retour au tableau de bord</a>
        
        <div class="filter">
            <form method="GET" action="/exams3-main/exams3/user/besoins">
                <label>🏙️ Filtrer par ville :</label>
                <select name="id_ville">
                    <option value="0">-- Toutes les villes --</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?= $v["id"] ?>" <?= ($id_ville == $v["id"]) ? "selected" : "" ?>>
                            <?= htmlspecialchars($v["nom"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filtrer</button>
                <?php if ($id_ville > 0): ?>
                    <a href="/exams3-main/exams3/user/besoins" style="color: #e74c3c; text-decoration: none;">✖️ Réinitialiser</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= count($besoins) ?></div>
                <div class="stat-label">Besoins<?= $id_ville > 0 ? ' dans cette ville' : ' au total' ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #f39c12;"><?= array_sum(array_column($besoins, 'nombre')) ?></div>
                <div class="stat-label">Quantité totale nécessaire</div>
            </div>
        </div>

        <?php if (empty($besoins)): ?>
            <div class="no-data">
                <h3>😊 Aucun besoin trouvé</h3>
                <p>Il n'y a actuellement aucun besoin enregistré<?= $id_ville > 0 ? ' pour cette ville' : '' ?>.</p>
                <?php if ($id_ville > 0): ?>
                    <a href="/exams3-main/exams3/user/besoins">Voir tous les besoins</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>🆔 ID</th>
                        <th>📝 Besoin</th>
                        <th>📊 Quantité Nécessaire</th>
                        <th>🏙️ Ville</th>
                        <th>🚨 Urgence</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($besoins as $besoin): ?>
                        <?php
                        $urgence = 'normale';
                        $urgenceClass = 'badge-normal';
                        if ($besoin['nombre'] > 1000) {
                            $urgence = 'très urgente';
                            $urgenceClass = 'badge-urgent';
                        } elseif ($besoin['nombre'] > 100) {
                            $urgence = 'modérée';
                            $urgenceClass = 'badge-faible';
                        }
                        ?>
                        <tr>
                            <td><strong>#<?= $besoin['id'] ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($besoin['nom']) ?></strong>
                            </td>
                            <td>
                                <span style="font-size: 18px; font-weight: bold; color: #e74c3c;">
                                    <?= number_format($besoin['nombre'], 0, ',', ' ') ?>
                                </span>
                            </td>
                            <td>
                                <span style="background: #e8f4f8; padding: 4px 8px; border-radius: 4px;">
                                    📍 <?= htmlspecialchars($besoin['ville_nom']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $urgenceClass ?>">
                                    <?= ucfirst($urgence) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #27ae60;">💡 Vous voulez aider ?</h3>
            <p>Consultez ces besoins et faites un don pour soutenir les sinistrés dans le besoin.</p>
            <a href="/exams3-main/exams3/user/dons" class="back-btn" style="background: #e74c3c;">❤️ Faire un Don Maintenant</a>
        </div>
    </div>
</body>
</html>