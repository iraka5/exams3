<?php
$base = '/exams3-main/exams3';

// Initialisation sécurisée des variables pour éviter les erreurs
if (!isset($regions)) $regions = [];
if (!isset($region_id)) $region_id = 0;
if (!isset($region_selected)) $region_selected = null;
if (!isset($villes)) $villes = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Villes - BNGRC</title>
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
      <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Gestion des Villes - BNGRC</h1>
      <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Administration des villes et communes par région</p>
    </div>

    <!-- Navigation avec boutons arrondis individuels -->
    <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
      <a href="/exams3-main/exams3/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
      <a href="/exams3-main/exams3/regions" class="btn btn-outline" style="border-radius: 25px;">Régions</a>
      <a href="/exams3-main/exams3/villes" class="btn btn-primary" style="border-radius: 25px;">Villes</a>
      <a href="/exams3-main/exams3/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
      <a href="/exams3-main/exams3/dons" class="btn btn-outline" style="border-radius: 25px;">Dons</a>
      <a href="/exams3-main/exams3/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
      <a href="/exams3-main/exams3/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
    </nav>

    <!-- Contenu principal -->

    <!-- Filtre par région -->
    <div class="filter-form">
      <form method="GET" action="<?= $base ?>/villes">
        <label>Filtrer par région :</label>
        <select name="region_id">
          <option value="0">-- Toutes les régions --</option>
          <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id'] ?>" <?= ($region_id == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">🔍 Filtrer</button>
      </form>

      <?php if ($region_id > 0): ?>
        <a href="<?= $base ?>/villes" class="btn" style="background: #6c757d;">🗑️ Réinitialiser</a>
      <?php endif; ?>
    </div>

    <!-- Affichage de la région filtrée -->
    <?php if ($region_selected): ?>
      <div class="region-info">
         Affichage des villes de la région : <strong><?= htmlspecialchars($region_selected['nom']) ?></strong>
      </div>
    <?php endif; ?>

    <!-- Bouton ajouter une ville -->
    <a href="<?= $base ?>/villes/create" class="btn btn-success">➕ Ajouter une ville</a>

    <!-- Tableau des villes -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom de la Ville</th>
          <th>Région</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($villes)): ?>
          <tr>
            <td colspan="4" class="no-data">
              <?php if ($region_selected): ?>
                Aucune ville dans la région "<?= htmlspecialchars($region_selected['nom']) ?>"
              <?php else: ?>
                Aucune ville enregistrée
              <?php endif; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($villes as $ville): ?>
            <tr>
              <td><?= $ville['id'] ?></td>
              <td><strong><?= htmlspecialchars($ville['nom']) ?></strong></td>
              <td>
                <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px;">
                  <?= htmlspecialchars($ville['region_nom']) ?>
                </span>
              </td>
              <td>
                <a href="<?= $base ?>/villes/<?= $ville['id'] ?>" class="btn">Voir</a>
                <a href="<?= $base ?>/villes/<?= $ville['id'] ?>/edit" class="btn btn-warning">Modifier</a>
                <a href="<?= $base ?>/villes/<?= $ville['id'] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette ville ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

</body>
</html>
