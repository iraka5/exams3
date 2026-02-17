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
    
    /* Stats cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      text-align: center;
    }
    .stat-value {
      font-size: 32px;
      font-weight: bold;
      color: var(--brand);
      margin: 10px 0;
    }
    .stat-label {
      color: var(--muted);
      font-size: 14px;
    }
    
    /* Table */
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
    
    .badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-info { background: #d1ecf1; color: #0c5460; }
    
    .btn {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 999px;
      background: var(--brand);
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      margin: 5px;
    }
    .btn:hover { opacity: 0.9; }
    .btn-success { background: #28a745; }
  </style>
</head>
<body>
  <div class="header">
    <h1>BNGRC - Tableau de Bord</h1>
    <p>Bureau National de Gestion des Risques et Catastrophes</p>
  </div>

  <nav>
    <a href="<?= $base ?>/" class="active">Accueil</a>
    <a href="<?= $base ?>/regions">Régions</a>
    <a href="<?= $base ?>/villes">Villes</a>
    <a href="<?= $base ?>/besoins">Besoins</a>
    <a href="<?= $base ?>/dons">Dons</a>
    <a href="<?= $base ?>/logout">Déconnexion</a>
  </nav>

  <div class="container">
    
    <!-- Statistiques générales -->
    <div class="stats-grid">
      <div class="stat-card">
        <div style="font-size: 40px;">🗺️</div>
        <div class="stat-value"><?= $stats['regions'] ?? 0 ?></div>
        <div class="stat-label">Régions</div>
      </div>
      <div class="stat-card">
        <div style="font-size: 40px;">🏘️</div>
        <div class="stat-value"><?= $stats['villes'] ?? 0 ?></div>
        <div class="stat-label">Villes</div>
      </div>
      <div class="stat-card">
        <div style="font-size: 40px;">📦</div>
        <div class="stat-value"><?= $stats['besoins'] ?? 0 ?></div>
        <div class="stat-label">Besoins</div>
      </div>
      <div class="stat-card">
        <div style="font-size: 40px;">🎁</div>
        <div class="stat-value"><?= $stats['dons'] ?? 0 ?></div>
        <div class="stat-label">Dons</div>
      </div>
    </div>

    <!-- Dernières régions ajoutées -->
    <h2>🗺️ Dernières régions</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($dernieres_regions)): ?>
          <tr><td colspan="3" style="text-align:center">Aucune région</td></tr>
        <?php else: ?>
          <?php foreach ($dernieres_regions as $r): ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['nom']) ?></td>
            <td>
              <a href="<?= $base ?>/regions/<?= $r['id'] ?>" class="btn">Voir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Dernières villes ajoutées -->
    <h2>🏘️ Dernières villes</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Région</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($dernieres_villes)): ?>
          <tr><td colspan="4" style="text-align:center">Aucune ville</td></tr>
        <?php else: ?>
          <?php foreach ($dernieres_villes as $v): ?>
          <tr>
            <td><?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['nom']) ?></td>
            <td><?= htmlspecialchars($v['region_nom']) ?></td>
            <td>
              <a href="<?= $base ?>/villes/<?= $v['id'] ?>" class="btn">Voir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Liens rapides -->
    <div style="text-align: center; margin: 40px 0;">
      <a href="<?= $base ?>/create" class="btn btn-success" style="padding: 15px 30px; font-size: 16px;">➕ Créer (Région, Ville, Besoin)</a>
    </div>

  </div>
</body>
</html>