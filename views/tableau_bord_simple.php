<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - BNGRC</title>
  <style>
    :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
    body { font-family: Inter, Segoe UI, Arial, sans-serif; background: var(--bg); margin:0; }
    .header { background: var(--brand); color:white; padding:20px; text-align:center; }
    .header h1 { margin:0; font-size:28px; }
    .header p { margin:5px 0 0 0; opacity:0.9; font-size:14px; }
    nav { background:white; padding:10px 20px; display:flex; gap:10px; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
    nav a { color:var(--brand); text-decoration:none; padding:8px 15px; border-radius:999px; font-weight:600; font-size:14px; background:rgba(19,38,92,0.08); }
    nav a:hover, nav a.active { background:var(--brand); color:white; }
    .container { max-width:1200px; margin:30px auto; padding:0 20px; }
    
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
    }
    .btn:hover { opacity: 0.9; }
    .btn-success { background: #28a745; }
    .btn-info { background: #17a2b8; }
    
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
    <a href="<?= $base ?>/" class="active">Accueil</a>
    <a href="<?= $base ?>/regions">Régions</a>
    <a href="<?= $base ?>/villes">Villes</a>
    <a href="<?= $base ?>/besoins">Besoins</a>
    <a href="<?= $base ?>/dons">Dons</a>
    <a href="<?= $base ?>/config-taux" style="background: #17a2b8; color: white;">Config V3</a>
    <a href="<?= $base ?>/reset-data" style="background: #ffc107; color: black;">Reset</a>
    <a href="<?= $base ?>/logout">Déconnexion</a>
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
                <a href="<?= $base ?>/villes/<?= $row['ville_id'] ?>" class="btn">Voir</a>
                <a href="<?= $base ?>/dons/create?ville_id=<?= $row['ville_id'] ?>" class="btn btn-success">➕ Don</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

<<<<<<< HEAD
    <!-- Fonctionnalités V3 -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; margin: 30px 0; color: white; text-align: center;">
      <h2 style="margin: 0 0 10px 0; color: white;">🚀 Nouvelles fonctionnalités V3</h2>
      <p style="margin: 0 0 20px 0; opacity: 0.9;">Système de vente d'articles et gestion configurable</p>
      <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="<?= $base ?>/config-taux" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
          ⚙️ Configurer taux de vente
        </a>
        <a href="<?= $base ?>/dons" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
          💰 Vendre des articles
        </a>
        <a href="<?= $base ?>/reset-data" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
          🔄 Réinitialiser données
        </a>
      </div>
    </div>

    <!-- Liens rapides -->
    <div style="text-align: center; margin: 40px 0;">
      <a href="<?= $base ?>/create" class="btn btn-success" style="padding: 15px 30px; font-size: 16px;">➕ Créer (Région, Ville, Besoin)</a>
=======
    <!-- Boutons d'action -->
    <div class="action-buttons">
      <a href="<?= $base ?>/dons/create" class="btn btn-info">🎁 Faire un don</a>
>>>>>>> 440cd6c1b68c915059324f07a8149f02f8ec3097
    </div>

  </div>
</body>
</html>