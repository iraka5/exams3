<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - BNGRC</title>
  <style>
    :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
    body { font-family: Inter, Segoe UI, Arial, sans-serif; background: var(--bg); margin:0; }
    .header { background: var(--brand); color:white; padding:20px; text-align:center; }
    nav { background:white; padding:10px 20px; display:flex; gap:10px; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
    nav a { color:var(--brand); text-decoration:none; padding:8px 15px; border-radius:999px; font-weight:600; font-size:14px; background:rgba(19,38,92,0.08); }
    nav a:hover, nav a.active { background:var(--brand); color:white; }
    .container { max-width:1200px; margin:30px auto; padding:0 20px; }
    table { width:100%; border-collapse:collapse; background:white; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); overflow:hidden; }
    th, td { padding:12px 15px; text-align:left; font-size:14px; }
    th { background:var(--brand); color:white; font-weight:600; }
    tr:nth-child(even) { background:#f9fafc; }
    tr:hover { background:rgba(19,38,92,0.05); }
    .no-data { text-align:center; color:var(--muted); margin-top:20px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>BNGRC - Bureau National de Gestion des Risques et Catastrophes</h1>
    <p>Tableau de bord - Suivi des dons aux sinistrés</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/" class="active">Accueil</a>
    <a href="/exams3-main/exams3/regions">Régions</a>
    <a href="/exams3-main/exams3/villes">Villes</a>
    <a href="/exams3-main/exams3/besoins">Besoins</a>
    <a href="/exams3-main/exams3/dons">Dons</a>
    <a href="/exams3-main/exams3/logout">Déconnexion</a>
  </nav>

  <div class="container">
    <h2>📊 Synthèse des besoins et dons</h2>
    <table>
      <thead>
        <tr>
          <th>Région</th>
          <th>Ville</th>
          <th>Type de besoin</th>
          <th>Quantité demandée</th>
          <th>Quantité donnée</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($stats)): ?>
          <?php foreach ($stats as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['region']); ?></td>
              <td><?php echo htmlspecialchars($row['ville']); ?></td>
              <td><?php echo htmlspecialchars($row['besoin']); ?></td>
              <td><?php echo htmlspecialchars($row['quantite_demandee']); ?></td>
              <td><?php echo htmlspecialchars($row['quantite_donnee']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="no-data">Aucune donnée disponible</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
