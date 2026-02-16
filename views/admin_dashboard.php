<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --success: #28a745; }
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { margin: 0; }
        .header a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; }
        .header a:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .section h2 { color: var(--brand); margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; font-weight: 600; }
        input[type="text"],
        input[type="number"],
        select { 
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e6e9ef;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-primary { background: var(--brand); color: white; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-logout { background: #dc3545; color: white; }
        .alert { padding: 12px 15px; margin-bottom: 15px; border-radius: 6px; font-size: 13px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        @media (max-width: 768px) { .form-row { flex-direction: column; } }
    </style>
</head>
<body>

<div class="header">
    <h1>👨‍💼 Tableau de Bord Admin</h1>
    <a href="/exams3-main/exams3/logout" class="btn btn-logout">Déconnexion</a>
</div>

<div class="container">
    <div class="alert alert-info">
        Bienvenue administrateur ! Gérez ici les régions, villes et besoins.
    </div>

    <!-- AJOUT REGION -->
    <div class="section">
        <h2>➕ Ajouter une Région</h2>
        <form method="post" action="/exams3-main/exams3/regions">
            <div class="form-group">
                <label for="region_nom">Nom de la région</label>
                <input id="region_nom" type="text" name="nom" placeholder="Ex: Analamanga" required>
            </div>
            <button class="btn btn-primary" type="submit">Ajouter une région</button>
        </form>
    </div>

    <!-- AJOUT VILLE -->
    <div class="section">
        <h2>🏘️ Ajouter une Ville</h2>
        <form method="post" action="/exams3-main/exams3/villes">
            <div class="form-row">
                <div class="form-group">
                    <label for="ville_nom">Nom de la ville</label>
                    <input id="ville_nom" type="text" name="nom" placeholder="Ex: Antananarivo" required>
                </div>
                <div class="form-group">
                    <label for="ville_region">Région</label>
                    <select id="ville_region" name="id_regions" required>
                        <option value="">-- Sélectionner une région --</option>
                        <?php
                        try {
                            $db = getDB();
                            $stmt = $db->query("SELECT id, nom FROM regions ORDER BY nom");
                            while ($region = $stmt->fetch()) {
                                echo "<option value=\"{$region['id']}\">{$region['nom']}</option>";
                            }
                        } catch (Exception $e) {
                            echo "<option disabled>Erreur: {$e->getMessage()}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Ajouter une ville</button>
        </form>
    </div>

    <!-- AJOUT BESOIN -->
    <div class="section">
        <h2>📦 Ajouter un Besoin</h2>
        <form method="post" action="/exams3-main/exams3/besoins">
            <div class="form-row">
                <div class="form-group">
                    <label for="besoin_nom">Type de besoin</label>
                    <input id="besoin_nom" type="text" name="nom" placeholder="Ex: Riz, Eau, Médicaments" required>
                </div>
                <div class="form-group">
                    <label for="besoin_nombre">Quantité</label>
                    <input id="besoin_nombre" type="number" name="nombre" placeholder="0.00" step="0.01" required>
                </div>
            </div>
            <div class="form-group">
                <label for="besoin_ville">Ville concernée</label>
                <select id="besoin_ville" name="id_ville" required>
                    <option value="">-- Sélectionner une ville --</option>
                    <?php
                    try {
                        $db = getDB();
                        $stmt = $db->query("SELECT id, nom FROM ville ORDER BY nom");
                        while ($ville = $stmt->fetch()) {
                            echo "<option value=\"{$ville['id']}\">{$ville['nom']}</option>";
                        }
                    } catch (Exception $e) {
                        echo "<option disabled>Erreur: {$e->getMessage()}</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Ajouter un besoin</button>
        </form>
    </div>

</div>

</body>
</html>
