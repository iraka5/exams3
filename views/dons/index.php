<?php
$base = '/exams3-main/exams3';
<<<<<<< HEAD
=======
if (!isset($dons)) $dons = [];
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<<<<<<< HEAD
  <meta charset="UTF-8">
  <title>Dons - BNGRC</title>
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
      <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Gestion des Dons - BNGRC</h1>
      <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Suivi des dons reçus par ville et type</p>
    </div>

    <!-- Navigation avec boutons arrondis individuels -->
    <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
      <a href="/exams3-main/exams3/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
      <a href="/exams3-main/exams3/regions" class="btn btn-outline" style="border-radius: 25px;">Régions</a>
      <a href="/exams3-main/exams3/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
      <a href="/exams3-main/exams3/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
      <a href="/exams3-main/exams3/dons" class="btn btn-primary" style="border-radius: 25px;">Dons</a>
      <a href="/exams3-main/exams3/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
      <a href="/exams3-main/exams3/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
    </nav>

    <!-- Contenu principal -->
    <div class="section-header">
      <h2 class="section-title">Liste des Dons</h2>
      <a href="/exams3-main/exams3/dons/create" class="btn btn-success">➕ Ajouter un Don</a>
    </div>
    
    <div class="table-container">
      <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Donneur</th>
          <th>Type</th>
          <th>Quantité</th>
          <th>Ville</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($dons)): ?>
          <tr>
            <td colspan="6" class="no-data">Aucun don trouvé.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($dons as $don): ?>
            <tr>
              <td><?= $don['id'] ?></td>
              <td><strong><?= htmlspecialchars($don['nom_donneur']) ?></strong></td>
              <td><?= htmlspecialchars($don['type_don']) ?></td>
              <td><?= number_format($don['nombre_don'], 0, ',', ' ') ?></td>
              <td>
                <span class="badge badge-info">
                  <?= htmlspecialchars($don['ville_nom']) ?>
                </span>
              </td>
              <td class="actions">
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>" class="action-btn" title="Voir">👁️</a>
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/edit" class="action-btn" title="Modifier">✏️</a>
                <?php if (!($don['vendu'] ?? false)): ?>
                  <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/vendre" class="btn btn-sm" style="background: #17a2b8; color: white;">💰 Vendre</a>
                <?php else: ?>
                  <span class="badge badge-success">✅ VENDU</span>
                <?php endif; ?>
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')" 
                   class="action-btn" title="Supprimer" style="color: var(--danger);">🗑️</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      </table>
    </div>
  </div>
=======
    <meta charset="UTF-8">
    <title>Dons - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
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
        
        h2 {
            color: var(--brand);
            margin: 0 0 20px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            background: var(--brand);
            color: white;
            text-decoration: none;
            margin-right: 5px;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-success { background: #28a745; }
        .btn-vendre { background: #17a2b8; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        th {
            background: var(--brand);
            color: white;
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e6e9ef;
            font-size: 14px;
        }
        tr:nth-child(even) { background: #f9fafc; }
        tr:hover { background: rgba(19,38,92,0.05); }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-vendu {
            background: #6c757d;
            color: white;
        }
        
        .action-buttons {
            margin: 20px 0;
        }
    </style>
    <script>
        function confirmVente() {
            return confirm("Confirmer la vente de ce don ?");
        }
    </script>
</head>
<body>

<div class="header">
    <h1>BNGRC - Gestion des Dons</h1>
    <p>Liste des dons reçus</p>
</div>

<nav>
    <a href="<?= $base ?>/">🏠 Accueil</a>
    <a href="<?= $base ?>/regions">🗺️ Régions</a>
    <a href="<?= $base ?>/villes">🏘️ Villes</a>
    <a href="<?= $base ?>/besoins">📋 Besoins</a>
    <a href="<?= $base ?>/dons" class="active">🎁 Dons</a>
    <a href="<?= $base ?>/ventes">💰 Ventes</a>
    <a href="<?= $base ?>/achats">📝 Achats</a>
    <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
    <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
</nav>

<div class="container">
    
    <div class="action-buttons">
        <a href="<?= $base ?>/dons/create" class="btn btn-success">➕ Nouveau don</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Donneur</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Ville</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dons)): ?>
                <tr>
                    <td colspan="7" class="no-data">Aucun don disponible</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= htmlspecialchars($don['id'] ?? '') ?></td>
                        <td><strong><?= htmlspecialchars($don['nom_donneur'] ?? '') ?></strong></td>
                        <td><?= htmlspecialchars($don['type_don'] ?? '') ?></td>
                        <td><?= number_format($don['nombre_don'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($don['ville_nom'] ?? '') ?></td>
                        <td>
                            <?php if (isset($don['statut']) && $don['statut'] === 'vendu'): ?>
                                <span class="badge badge-vendu">VENDU</span>
                            <?php else: ?>
                                <span class="badge" style="background: #28a745; color: white;">DISPONIBLE</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!isset($don['statut']) || $don['statut'] !== 'vendu'): ?>
                                <a href="<?= $base ?>/dons/vendre?id=<?= $don['id'] ?>" class="btn btn-vendre" onclick="return confirmVente();">💰 Vendre</a>
                            <?php endif; ?>
                            <a href="<?= $base ?>/dons/<?= $don['id'] ?>/delete" class="btn" style="background: #dc3545;" onclick="return confirm('Supprimer ce don ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299

</body>
</html>