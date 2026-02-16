<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Régions - BNGRC</title>
  <style>
    :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
    body { font-family: Inter, Segoe UI, Arial, sans-serif; background: var(--bg); margin:0; }
    .header { background: var(--brand); color:white; padding:20px; text-align:center; }
    nav { background:white; padding:10px 20px; display:flex; gap:10px; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
    nav a { color:var(--brand); text-decoration:none; padding:8px 15px; border-radius:999px; font-weight:600; font-size:14px; background:rgba(19,38,92,0.08); }
    nav a:hover, nav a.active { background:var(--brand); color:white; }
    .container { max-width:1200px; margin:30px auto; padding:0 20px; }
    .btn { display:inline-block; padding:10px 20px; border-radius:999px; border:none; cursor:pointer; font-weight:600; font-size:14px; background:var(--brand); color:white; text-decoration:none; }
    .btn-success { background:#28a745; }
    .btn-danger { background:#dc3545; }
    .btn-warning { background:#ffc107; color:black; }
    table { width:100%; border-collapse:collapse; background:white; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); overflow:hidden; margin-top:20px; }
    th, td { padding:12px 15px; text-align:left; font-size:14px; border-bottom:1px solid #e6e9ef; }
    th { background:var(--brand); color:white; font-weight:600; }
    tr:nth-child(even) { background:#f9fafc; }
    tr:hover { background:rgba(19,38,92,0.05); }
    .no-data { text-align:center; color:var(--muted); padding:20px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>🗺️ Gestion des Régions - BNGRC</h1>
    <p>Bureau National de Gestion des Risques et Catastrophes</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/">🏠 Accueil</a>
    <a href="/exams3-main/exams3/regions" class="active">🗺️ Régions</a>
    <a href="/exams3-main/exams3/villes">🏘️ Villes</a>
    <a href="/exams3-main/exams3/besoins">📦 Besoins</a>
    <a href="/exams3-main/exams3/dons">🎁 Dons</a>
    <a href="/exams3-main/exams3/tableau-bord">📊 Tableau de bord</a>
    <a href="/exams3-main/exams3/logout">🚪 Déconnexion</a>
  </nav>

  <div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
      <h2>Liste des Régions</h2>
      <a href="/exams3-main/exams3/regions/create" class="btn btn-success">➕ Ajouter une région</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom de la Région</th>
          <th>Nombre de Villes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($regions)): ?>
          <tr>
            <td colspan="4" class="no-data">Aucune région enregistrée</td>
          </tr>
        <?php else: ?>
          <?php foreach ($regions as $region): ?>
            <tr>
              <td><?= $region["id"] ?></td>
              <td><strong><?= htmlspecialchars($region["nom"]) ?></strong></td>
              <td>
                <span style="background:#e8f5e8; padding:3px 8px; border-radius:15px;">
                  <?= $region["nb_villes"] ?> ville(s)
                </span>
              </td>
              <td>
                <a href="/exams3-main/exams3/regions/<?= $region["id"] ?>" class="btn">👁️ Voir</a>
                <a href="/exams3-main/exams3/regions/<?= $region["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
                <a href="/exams3-main/exams3/regions/<?= $region["id"] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette région ?')">🗑️ Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
