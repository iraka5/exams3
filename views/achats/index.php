<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Achats - BNGRC</title>
    
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
=======
  <meta charset="UTF-8">
  <title>Achats - BNGRC</title>
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

    .btn {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 999px;
      background: var(--brand);
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      border: none;
      cursor: pointer;
      transition: opacity 0.3s;
    }
    .btn:hover { opacity: 0.9; }
    .btn-success { background: var(--success); }
    .btn-warning { background: var(--warning); color: black; }
    .btn-info { background: var(--info); }

    .filter-section {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      margin-bottom: 20px;
    }
    .filter-form {
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }
    select, button {
      padding: 10px 15px;
      border: 1px solid #e6e9ef;
      border-radius: 8px;
      font-size: 14px;
    }
    select:focus, button:focus {
      outline: none;
      border-color: var(--brand);
    }

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
      display: inline-block;
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .badge-nature { background: #d4edda; color: #155724; }
    .badge-materiaux { background: #fff3cd; color: #856404; }
    .badge-argent { background: #d1ecf1; color: #0c5460; }

    .no-data {
      text-align: center;
      padding: 40px;
      color: var(--muted);
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .actions {
      display: flex;
      gap: 10px;
      margin: 20px 0;
    }
  </style>
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299
</head>
<body>
  <div class="header">
    <h1>💰 Gestion des Achats</h1>
    <p>Suivi des achats réalisés avec les dons en argent</p>
  </div>

  <nav>
    <a href="<?= $base ?>/">🏠 Accueil</a>
    <a href="<?= $base ?>/regions">🗺️ Régions</a>
    <a href="<?= $base ?>/villes">🏘️ Villes</a>
    <a href="<?= $base ?>/besoins">📋 Besoins</a>
    <a href="<?= $base ?>/dons">🎁 Dons</a>
    <a href="<?= $base ?>/ventes">💰 Ventes</a>
    <a href="<?= $base ?>/achats" class="active">📝 Achats</a>
    <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
    <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
  </nav>

  <div class="container">

    <div class="actions">
      <a href="<?= $base ?>/achats/create" class="btn btn-success">➕ Nouvel achat</a>
      <a href="<?= $base ?>/achats/recapitulatif" class="btn btn-info">📊 Récapitulatif</a>
    </div>

    <div class="filter-section">
      <form method="GET" class="filter-form">
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
        <button type="submit" class="btn">🔍 Filtrer</button>
        <a href="<?= $base ?>/achats" class="btn" style="background: var(--muted);">🗑️ Réinitialiser</a>
      </form>
    </div>

    <?php if (empty($achats ?? [])): ?>
      <div class="no-data">
        <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
        <h3>Aucun achat trouvé</h3>
        <p>Il n'y a pas encore d'achats enregistrés.</p>
        <a href="<?= $base ?>/achats/create" class="btn btn-success" style="margin-top: 15px;">Créer le premier achat</a>
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
          <?php foreach ($achats as $achat): ?>
            <tr>
              <td><?= date('d/m/Y H:i', strtotime($achat['date_achat'] ?? $achat['created_at'])) ?></td>
              <td>
                <strong><?= htmlspecialchars($achat['ville_nom'] ?? '') ?></strong><br>
                <small style="color: var(--muted);"><?= htmlspecialchars($achat['region_nom'] ?? '') ?></small>
              </td>
              <td><?= htmlspecialchars($achat['besoin_nom'] ?? $achat['besoin_description'] ?? '') ?></td>
              <td>
                <?php 
                $type = $achat['type_besoin'] ?? 'nature';
                $badge_class = 'badge-' . $type;
                ?>
                <span class="badge <?= $badge_class ?>">
                  <?= ucfirst($type) ?>
                </span>
              </td>
              <td><?= number_format($achat['quantite'], 0, ',', ' ') ?></td>
              <td><?= number_format($achat['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
              <td><strong style="color: var(--success);"><?= number_format($achat['montant'] ?? $achat['montant_total'], 0, ',', ' ') ?> Ar</strong></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
      <a href="<?= $base ?>/tableau-bord" class="btn">← Retour au tableau de bord</a>
    </div>
  </div>
</body>
</html>