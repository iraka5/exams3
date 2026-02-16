<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Villes - BNGRC</title>
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

    .filter-form {
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      margin-bottom: 20px;
    }
    select, button {
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #e6e9ef;
      font-size: 14px;
    }

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
    <h1> Gestion des Villes - BNGRC</h1>
    <p>Administration des villes et communes par région</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/">Accueil</a>
    <a href="/exams3-main/exams3/regions">Régions</a>
    <a href="/exams3-main/exams3/villes" class="active">Villes</a>
    <a href="/exams3-main/exams3/besoins">Besoins</a>
    <a href="/exams3-main/exams3/dons">Dons</a>
    <a href="/exams3-main/exams3/logout">Déconnexion</a>
  </nav>

  <div class="container">

    <div class="filter-form">
      <form method="GET" action="/exams3-main/exams3/villes">
        <label>Filtrer par région :</label>
        <select name="region_id">
          <option value="0">-- Toutes les régions --</option>
          <?php foreach ($regions as $r): ?>
            <option value="<?= $r["id"] ?>" <?= ($region_id == $r["id"]) ? "selected" : "" ?>>
              <?= htmlspecialchars($r["nom"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">🔍 Filtrer</button>
      </form>
    </div>

    <?php if ($region_selected): ?>
      <div style="background: #e8f5e8; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
         Affichage des villes de la région : <strong><?= htmlspecialchars($region_selected["nom"]) ?></strong>
      </div>
    <?php endif; ?>

    <a href="/exams3-main/exams3/villes/create" class="btn btn-success">➕ Ajouter une ville</a>

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
                Aucune ville dans la région "<?= htmlspecialchars($region_selected["nom"]) ?>"
              <?php else: ?>
                Aucune ville enregistrée
              <?php endif; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($villes as $ville): ?>
            <tr>
              <td><?= $ville["id"] ?></td>
              <td><strong><?= htmlspecialchars($ville["nom"]) ?></strong></td>
              <td>
                <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px;">
                  <?= htmlspecialchars($ville["region_nom"]) ?>
                </span>
              </td>
              <td>
                <a href="/exams3-main/exams3/villes/<?= $ville["id"] ?>" class="btn"> Voir</a>
                <a href="/exams3-main/exams3/villes/<?= $ville["id"] ?>/edit" class="btn btn-warning"> Modifier</a>
                <a href="/exams3-main/exams3/villes/<?= $ville["id"] ?>/delete" 
                   class="btn btn-danger" 
                   onclick="return confirm('Supprimer cette ville ?')"> Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px;">
      <a href="/exams3-main/exams3/tableau-bord" class="btn" style="background: #6f42c1;">
         Voir le Tableau de Bord
      </a>
    </div>
  </div>

</body>
</html>
