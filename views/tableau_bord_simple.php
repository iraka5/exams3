<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - BNGRC</title>
  <style>
    :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --success: #28a745; --info: #17a2b8; --warning: #ffc107; --danger: #dc3545; }
    * { box-sizing: border-box; }
    body {
      font-family: Inter, Segoe UI, Arial, sans-serif;
      background: var(--bg);
      margin: 0;
      padding: 0;
    }
    .header {
      background: var(--brand);
      color: white;
      padding: 20px;
      text-align: center;
    }
    .header h1 { margin: 0; font-size: 28px; }
    .header p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; }

    nav {
      background: white;
      padding: 10px 20px;
      display: flex;
      gap: 10px;
      justify-content: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      flex-wrap: wrap;
    }
    nav a {
      color: var(--brand);
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 14px;
      background: rgba(19,38,92,0.08);
      transition: all 0.3s;
    }
    nav a:hover, nav a.active {
      background: var(--brand);
      color: white;
    }

    .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
    
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow: hidden;
      margin-top: 20px;
    }
    th {
      background: var(--brand);
      color: white;
      padding: 15px;
      text-align: left;
      font-weight: 600;
    }
    td {
      padding: 12px 15px;
      border-bottom: 1px solid #e6e9ef;
    }
    tr:nth-child(even) { background: #f9fafc; }
    tr:hover { background: rgba(19,38,92,0.05); }
    
    .region-badge {
      background: #e3f2fd;
      color: var(--brand);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }
    
    .btn {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 999px;
      background: var(--brand);
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      margin: 2px;
      border: none;
      cursor: pointer;
      transition: opacity 0.3s;
    }
    .btn:hover { opacity: 0.9; }
    .btn-success { background: var(--success); }
    .btn-info { background: var(--info); }
    .btn-warning { background: var(--warning); color: black; }
    .btn-danger { background: var(--danger); }
    
    .progress-bar {
      width: 100%;
      height: 8px;
      background: #ecf0f1;
      border-radius: 4px;
      overflow: hidden;
      margin-top: 5px;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #27ae60, #2ecc71);
      transition: width 0.3s;
    }
    
    .actions {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    
    h2 {
      color: var(--brand);
      margin: 30px 0 15px 0;
    }
    
    .feature-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 30px;
      border-radius: 12px;
      margin: 30px 0;
      color: white;
      text-align: center;
    }
    .feature-section h2 {
      color: white;
      margin: 0 0 10px 0;
    }
    .feature-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 20px;
    }
    .feature-buttons .btn {
      background: rgba(255,255,255,0.2);
      color: white;
      border: 1px solid rgba(255,255,255,0.3);
      padding: 12px 24px;
      font-size: 14px;
    }
    .feature-buttons .btn:hover {
      background: rgba(255,255,255,0.3);
    }

    .action-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin: 40px 0;
      flex-wrap: wrap;
    }
    .action-buttons .btn {
      padding: 15px 30px;
      font-size: 16px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>BNGRC - Tableau de Bord</h1>
    <p>Suivi des besoins et dons par ville</p>
  </div>

  <nav>
    <a href="<?= $base ?>/" class="active">🏠 Accueil</a>
    <a href="<?= $base ?>/regions">🗺️ Régions</a>
    <a href="<?= $base ?>/villes">🏘️ Villes</a>
    <a href="<?= $base ?>/besoins">📋 Besoins</a>
    <a href="<?= $base ?>/dons">🎁 Dons</a>
    <a href="<?= $base ?>/ventes">💰 Ventes</a>
    <a href="<?= $base ?>/achats">📝 Achats</a>
    <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
    <a href="<?= $base ?>/reset-data">🔄 Reset</a>
    <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
  </nav>

  <div class="container">
    
    <h2>📋 Suivi des besoins par ville</h2>
    
    <table>
      <thead>
        <tr>
          <th>Région</th>
          <th>Ville</th>
          <th>Type de besoin</th>
          <th>Quantité nécessaire</th>
          <th>Dons reçus</th>
          <th>Progression</th>
          <th>Actions</th>
        </tr>
      </thead>
            <tbody>
        <?php if (empty($donnees)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--muted);">
                    Aucune donnée disponible. <a href="<?= $base ?>/create">Ajouter des données</a>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($donnees as $row): ?>
                <tr>
                    <td>
                        <span class="region-badge"><?= htmlspecialchars($row['region_nom']) ?></span>
                    </td>
                    <td><strong><?= htmlspecialchars($row['ville_nom']) ?></strong></td>
                    <td><?= htmlspecialchars($row['type_besoin']) ?></td>
                    <td><?= number_format($row['besoin_quantite'], 0, ',', ' ') ?></td>
                    <td><?= number_format($row['dons_quantite'], 0, ',', ' ') ?></td>
                    <td style="min-width: 150px;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $row['progression'] ?>%"></div>
                        </div>
                        <small><?= number_format($row['progression'], 1) ?>%</small>
                    </td>
                    <td class="actions">
                            <?php 
                            $ville_id = isset($row['ville_id']) ? intval($row['ville_id']) : 0; 
                            ?>
                            <a href="<?= $base ?>/villes/<?= $ville_id ?>" class="btn">👁️ Voir</a>
                            <a href="<?= $base ?>/dons/create?ville_id=<?= $ville_id ?>" class="btn btn-success">➕ Don</a>
                     </td>

                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>

    </table>

    <!-- Section Ventes -->
    <div class="feature-section">
      <h2>🚀 Système de Vente d'Articles</h2>
      <p>Convertissez les dons en argent selon un taux configurable</p>
      <div class="feature-buttons">
        <a href="<?= $base ?>/config-taux" class="btn">⚙️ Configurer taux de vente</a>
        <a href="<?= $base ?>/dons" class="btn">💰 Vendre des articles</a>
        <a href="<?= $base ?>/ventes" class="btn">📊 Historique des ventes</a>
        <a href="<?= $base ?>/reset-data" class="btn">🔄 Réinitialiser données</a>
      </div>
    </div>

    <!-- Actions rapides -->
    <div class="action-buttons">
      <a href="<?= $base ?>/create" class="btn btn-success">➕ Créer (Région, Ville, Besoin)</a>
      <a href="<?= $base ?>/dons/create" class="btn btn-info">🎁 Faire un don</a>
      <a href="<?= $base ?>/achats/create" class="btn btn-warning">💰 Nouvel achat</a>
    </div>

  </div>
</body>
</html>