<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - BNGRC</title>
  <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
</head>
<body>
  <div class="header">
    <h1>BNGRC - Tableau de Bord</h1>
    <p style="font-weight: 300; font-size: 0.9rem; opacity: 0.8;">Suivi des besoins et dons par ville</p>
  </div>

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
  </div>

  <!-- Dernières régions ajoutées -->
  <div style="text-align: center; margin: 40px 0;">
    <a href="<?= $base ?>/create" class="btn btn-success btn-lg">Créer (Région, Ville, Besoin)</a>
  </div>

  <!-- Dernières régions ajoutées -->
  <div class="section-header">
    <h2 class="section-title">🗺️ Dernières régions</h2>
    <a href="<?= $base ?>/regions" class="section-link">Voir toutes les régions →</a>
  </div>
  
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($dernieres_regions)): ?>
          <tr>
            <td colspan="4" style="text-align: center; padding: 30px; color: #95a5a6;">
              Aucune région enregistrée
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($dernieres_regions as $r): ?>
          <tr>
            <td><strong>#<?= $r['id'] ?></strong></td>
            <td><?= htmlspecialchars($r['nom']) ?></td>
            <td><?= isset($r['created_at']) ? date('d/m/Y', strtotime($r['created_at'])) : 'N/A' ?></td>
            <td>
              <a href="<?= $base ?>/regions/<?= $r['id'] ?>" class="btn btn-primary">Voir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Dernières villes ajoutées -->
  <div class="section-header">
    <h2 class="section-title">🏘️ Dernières villes</h2>
    <a href="<?= $base ?>/villes" class="section-link">Voir toutes les villes →</a>
  </div>
  
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Région</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($dernieres_villes)): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 30px; color: #95a5a6;">
              Aucune ville enregistrée
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($dernieres_villes as $v): ?>
          <tr>
            <td><strong>#<?= $v['id'] ?></strong></td>
            <td><?= htmlspecialchars($v['nom']) ?></td>
            <td><?= htmlspecialchars($v['region_nom'] ?? 'N/A') ?></td>
            <td><?= isset($v['created_at']) ? date('d/m/Y', strtotime($v['created_at'])) : 'N/A' ?></td>
            <td>
              <a href="<?= $base ?>/villes/<?= $v['id'] ?>" class="btn btn-primary">Voir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Derniers besoins -->
  <div class="section-header">
    <h2 class="section-title">📦 Derniers besoins</h2>
    <a href="<?= $base ?>/besoins" class="section-link">Voir tous les besoins →</a>
  </div>
  
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Besoin</th>
          <th>Ville</th>
          <th>Quantité</th>
          <th>Urgence</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($derniers_besoins)): ?>
          <tr>
            <td colspan="6" style="text-align: center; padding: 30px; color: #95a5a6;">
              Aucun besoin enregistré
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($derniers_besoins as $b): ?>
          <tr>
            <td><strong>#<?= $b['id'] ?></strong></td>
            <td><?= htmlspecialchars($b['nom']) ?></td>
            <td><?= htmlspecialchars($b['ville_nom'] ?? 'N/A') ?></td>
            <td><?= $b['quantite'] ?? 0 ?></td>
            <td>
              <?php if (($b['quantite'] ?? 0) > 100): ?>
                <span class="badge badge-warning">Urgent</span>
              <?php elseif (($b['quantite'] ?? 0) > 50): ?>
                <span class="badge badge-info">Modéré</span>
              <?php else: ?>
                <span class="badge badge-success">Faible</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="<?= $base ?>/besoins/<?= $b['id'] ?>" class="btn btn-primary">Voir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<?php include __DIR__ . '/partials/footer.php'; ?>