<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dons - BNGRC</title>
  <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
  <style>
    :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
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
    .header h1 { margin: 0; font-size: 22px; }
    .header p { margin: 5px 0 0; font-size: 14px; color: rgba(255,255,255,0.8); }

    nav {
      background: white;
      padding: 10px 20px;
      display: flex;
      gap: 10px;
      justify-content: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    nav a {
      color: var(--brand);
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 14px;
      background: rgba(19,38,92,0.08);
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
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      background: var(--brand);
      color: white;
      text-decoration: none;
    }
    .btn:hover { opacity: 0.9; }
    .btn-success { background: #28a745; }
    .btn-danger { background: #dc3545; }
    .btn-warning { background: #ffc107; color: black; }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow: hidden;
      margin-top: 20px;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      font-size: 14px;
      border-bottom: 1px solid #e6e9ef;
    }
    th {
      background: var(--brand);
      color: white;
      font-weight: 600;
    }
    tr:nth-child(even) { background: #f9fafc; }
    tr:hover { background: rgba(19,38,92,0.05); }

    .no-data {
      text-align: center;
      color: var(--muted);
      padding: 20px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1> Gestion des Dons - BNGRC</h1>
    <p>Suivi des dons reçus par ville et type</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/">Accueil</a>
    <a href="/exams3-main/exams3/regions">Régions</a>
    <a href="/exams3-main/exams3/villes">Villes</a>
    <a href="/exams3-main/exams3/besoins">Besoins</a>
    <a href="/exams3-main/exams3/dons" class="active">Dons</a>
    <a href="/exams3-main/exams3/config-taux">Config V3</a>
    <a href="/exams3-main/exams3/reset-data">Reset</a>
    <a href="/exams3-main/exams3/logout">Déconnexion</a>
  </nav>

  <div class="container">
    <a href="/exams3-main/exams3/dons/create" class="btn btn-success"> Ajouter un Don</a>

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
                <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px;">
                  <?= htmlspecialchars($don['ville_nom']) ?>
                </span>
              </td>
              <td>
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>" class="btn"> Voir</a>
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/edit" class="btn btn-warning"> Modifier</a>
                <?php if (!($don['vendu'] ?? false)): ?>
                  <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/vendre" class="btn" style="background: #17a2b8; color: white;"> Vendre</a>
                <?php else: ?>
                  <span style="background: #6c757d; color: white; padding: 8px 12px; border-radius: 999px; font-size: 12px;">VENDU</span>
                <?php endif; ?>
                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')" 
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
