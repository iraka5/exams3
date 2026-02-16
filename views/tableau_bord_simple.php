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
    <a href="/exams3-main/exams3/achats">💰 Achats</a>
    <a href="/exams3-main/exams3/achats/recapitulatif">📊 Récap</a>
    <a href="/exams3-main/exams3/logout">Déconnexion</a>
  </nav>

  <div class="container">
    <?php if (isset($error)): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
      ⚠️ <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($totaux)): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
      <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size: 24px; font-weight: bold; color: #e74c3c;"><?= number_format($totaux['besoins_total'] ?? 0, 0, ',', ' ') ?> Ar</div>
        <div style="color: #6b7280; font-size: 12px;">Besoins totaux</div>
      </div>
      <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size: 24px; font-weight: bold; color: #27ae60;"><?= number_format($totaux['besoins_satisfaits'] ?? 0, 0, ',', ' ') ?> Ar</div>
        <div style="color: #6b7280; font-size: 12px;">Achats effectués</div>
      </div>
      <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size: 24px; font-weight: bold; color: #3498db;"><?= number_format($totaux['dons_recus'] ?? 0, 0, ',', ' ') ?> Ar</div>
        <div style="color: #6b7280; font-size: 12px;">Dons reçus</div>
      </div>
      <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size: 24px; font-weight: bold; color: #8e44ad;"><?= number_format($totaux['fonds_restants'] ?? 0, 0, ',', ' ') ?> Ar</div>
        <div style="color: #6b7280; font-size: 12px;">Fonds disponibles</div>
      </div>
    </div>
    <?php endif; ?>
    
    <h2>💰 Montants d'achats par ville</h2>
    <table>
      <thead>
        <tr>
          <th>Région</th>
          <th>Ville</th>
          <th>Nombre d'achats</th>
          <th>Montant total des achats</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($achats_par_ville)): ?>
          <?php foreach ($achats_par_ville as $ville): ?>
            <tr>
              <td><strong><?= htmlspecialchars($ville['region_nom']) ?></strong></td>
              <td><?= htmlspecialchars($ville['ville_nom']) ?></td>
              <td style="text-align: center;">
                <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px; font-weight: bold;">
                  <?= $ville['nb_achats'] ?>
                </span>
              </td>
              <td style="text-align: right;">
                <strong style="color: <?= $ville['montant_achats'] > 0 ? '#27ae60' : '#6b7280' ?>;">
                  <?= number_format($ville['montant_achats'], 0, ',', ' ') ?> Ar
                </strong>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="no-data">Aucune donnée d'achat disponible.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
