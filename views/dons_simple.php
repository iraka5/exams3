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

    .alert {
      padding: 15px;
      margin: 20px 0;
      border-radius: 8px;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
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
    <h1>🎁 Gestion des Dons - BNGRC</h1>
    <p>Suivi des dons reçus pour les sinistrés</p>
  </div>

  <nav>
    <a href="/exams3-main/exams3/">🏠 Accueil</a>
    <a href="/exams3-main/exams3/regions">🗺️ Régions</a>
    <a href="/exams3-main/exams3/villes">🏘️ Villes</a>
    <a href="/exams3-main/exams3/besoins">📦 Besoins</a>
    <a href="/exams3-main/exams3/dons" class="active">🎁 Dons</a>
    <a href="/exams3-main/exams3/tableau-bord">📊 Tableau de bord</a>
    <a href="/exams3-main/exams3/logout">🚪 Déconnexion</a>
  </nav>

  <div class="container">
    <div class="alert">
      <strong>✅ Information :</strong> Système en configuration. Liste des dons temporairement indisponible.
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2>Liste des Dons</h2>
      <a href="/exams3-main/exams3/dons/create" class="btn btn-success">➕ Enregistrer un don</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Donneur</th>
          <th>Type de don</th>
          <th>Quantité</th>
          <th>Destination</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>ONU Madagascar</td>
          <td>Eau potable</td>
          <td>800 L</td>
          <td>Antananarivo</td>
        </tr>
        <tr>
          <td>Croix Rouge</td>
          <td>Vivres</td>
          <td>300 kg</td>
          <td>Antsirabe</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
