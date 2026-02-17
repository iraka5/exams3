<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Besoins - BNGRC</title>
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

    .filter {
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
    <h1> Gestion des Besoins - BNGRC</h1>
    <p>Suivi des besoins des sinistrés par ville</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/">Accueil</a>
    <a href="/exams3-main/exams3/regions">Régions</a>
    <a href="/exams3-main/exams3/villes">Villes</a>
    <a href="/exams3-main/exams3/besoins" class="active">Besoins</a>
    <a href="/exams3-main/exams3/dons">Dons</a>
    <a href="/exams3-main/exams3/logout">Sortir</a>
  </nav>

  <div class="container">

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
