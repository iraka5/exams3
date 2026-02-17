<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Besoins - BNGRC</title>
  <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
</head>
<body>
  <div class="container">
    <!-- Header avec logo BNGRC -->
    <header class="header">
      <div class="logo">
        BNG<span>RC</span>
      </div>
    </header>

    <!-- Hero avec fond du thème -->
    <div class="hero" style="background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--accent-blue-light) 50%, var(--bg-secondary) 100%); padding: 2.5rem; border-radius: 16px; margin-bottom: 2rem; text-align: center; border: 1px solid var(--border-light);">
      <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Gestion des Besoins - BNGRC</h1>
      <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Suivi des besoins des sinistrés par ville</p>
    </div>

    <!-- Navigation avec boutons arrondis individuels -->
    <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
      <a href="/exams3-main/exams3/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
      <a href="/exams3-main/exams3/regions" class="btn btn-outline" style="border-radius: 25px;">Régions</a>
      <a href="/exams3-main/exams3/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
      <a href="/exams3-main/exams3/besoins" class="btn btn-primary" style="border-radius: 25px;">Besoins</a>
      <a href="/exams3-main/exams3/dons" class="btn btn-outline" style="border-radius: 25px;">Dons</a>
      <a href="/exams3-main/exams3/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
      <a href="/exams3-main/exams3/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
    </nav>

    <!-- Contenu principal -->

    <div class="filter">
      <form method="GET" action="/exams3-main/exams3/besoins">
        <label>Filtrer par ville :</label>
        <select name="id_ville">
          <option value="0">-- Toutes les villes --</option>
          <?php foreach ($villes as $v): ?>
            <option value="<?= $v["id"] ?>" <?= ($id_ville == $v["id"]) ? "selected" : "" ?>>
              <?= htmlspecialchars($v["nom"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn"> Filtrer</button>
      </form>
      <a href="/exams3-main/exams3/besoins/create" class="btn btn-success"> Ajouter un Besoin</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Type</th>
          <th>Quantité</th>
          <th>Prix unitaire</th>
          <th>Montant total</th>
          <th>Ville</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($besoins)): ?>
          <tr>
            <td colspan="8" class="no-data">Aucun besoin trouvé.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($besoins as $besoin): ?>
            <tr>
              <td><?= $besoin['id'] ?></td>
              <td><strong><?= htmlspecialchars($besoin['nom']) ?></strong></td>
              <td>
                <?php 
                $type_color = [
                  'nature' => '#27ae60',
                  'materiaux' => '#e67e22', 
                  'argent' => '#3498db'
                ][$besoin['type_besoin'] ?? 'nature'];
                ?>
                <span style="background: <?= $type_color ?>20; color: <?= $type_color ?>; padding: 3px 8px; border-radius: 15px; font-size: 12px; font-weight: bold;">
                  <?= ucfirst($besoin['type_besoin'] ?? 'nature') ?>
                </span>
              </td>
              <td><?= number_format($besoin['nombre'], 0, ',', ' ') ?></td>
              <td><strong><?= number_format($besoin['prix_unitaire'] ?? 0, 0, ',', ' ') ?> Ar</strong></td>
              <td>
                <?php $montant_total = $besoin['nombre'] * ($besoin['prix_unitaire'] ?? 0); ?>
                <strong style="color: #e74c3c;"><?= number_format($montant_total, 0, ',', ' ') ?> Ar</strong>
              </td>
              <td>
                <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px;">
                  <?= htmlspecialchars($besoin['ville_nom']) ?>
                </span>
              </td>
              <td>
                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>" class="btn"> Voir</a>
                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/edit" class="btn btn-warning"> Modifier</a>
                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')" 
                   class="btn btn-danger"> Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
