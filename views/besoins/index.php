<?php
$base = '/exams3-main/exams3';

if (!isset($villes)) $villes = [];
if (!isset($besoins)) $besoins = [];
if (!isset($id_ville)) $id_ville = 0;
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

        h2 {
            color: var(--brand);
            margin: 0 0 20px 0;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.3s;
            margin-right: 5px;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: var(--brand); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-warning { background: var(--warning); color: black; }
        .btn-info { background: #17a2b8; color: white; }

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
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .montant-total {
            font-weight: bold;
            color: var(--success);
        }
    </style>
</head>
<body>

<div class="header">
    <h1>BNGRC - Gestion des Besoins</h1>
    <p>Liste des besoins par ville</p>
</div>

<nav>
    <a href="<?= $base ?>/">🏠 Accueil</a>
    <a href="<?= $base ?>/regions">🗺️ Régions</a>
    <a href="<?= $base ?>/villes">🏘️ Villes</a>
    <a href="<?= $base ?>/besoins" class="active">📋 Besoins</a>
    <a href="<?= $base ?>/dons">🎁 Dons</a>
    <a href="<?= $base ?>/ventes">💰 Ventes</a>
    <a href="<?= $base ?>/achats">📝 Achats</a>
    <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
    <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
</nav>

<div class="container">
    
    <div style="margin-bottom: 20px;">
        <a href="<?= $base ?>/besoins/create" class="btn btn-success">➕ Nouveau besoin</a>
    </div>

    <div class="filter-section">
        <form method="GET" class="filter-form">
            <label for="id_ville">Filtrer par ville :</label>
            <select name="id_ville" id="id_ville">
                <option value="0">-- Toutes les villes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v['id'] ?? 0) ?>" <?= ($id_ville == ($v['id'] ?? 0)) ? "selected" : "" ?>>
                        <?= htmlspecialchars($v['nom'] ?? '-') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <?php if ($id_ville > 0): ?>
                <a href="<?= $base ?>/besoins" class="btn btn-warning">Réinitialiser</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Affichage ville filtrée -->
    <?php if ($ville_selected): ?>
        <div class="ville-info">
            Affichage des besoins pour la ville : <strong><?= htmlspecialchars($ville_selected['nom'] ?? '-') ?></strong>
        </div>
    <?php endif; ?>

    <!-- Tableau des besoins -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Besoin</th>
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
                    <td colspan="8" class="no-data">Aucun besoin trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($besoins as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($b['description'] ?? '-') ?></td>
                        <td><?= number_format(floatval($b['montant'] ?? 0), 2, ',', ' ') ?> Ar</td>
                        <td><?= htmlspecialchars($b['ville_nom'] ?? '-') ?></td>
                        <td>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>" class="btn">Voir</a>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>/edit" class="btn" style="background: #ffc107; color: black;">Modifier</a>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>/delete" class="btn" style="background: #dc3545;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>