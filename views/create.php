<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer - Admin BNGRC</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { margin: 0; }
        .header a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; }
        .header a:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .card h2 { color: var(--brand); margin-top: 0; font-size: 18px; }
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
        }
        .btn:hover { opacity: 0.9; }
        .btn-back { background: #6b7280; margin-right: 10px; }
        .btn-back:hover { opacity: 0.9; }
        .actions { display: flex; gap: 10px; margin-top: 20px; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
        .footer-link { text-align: center; margin-top: 40px; }
        .footer-link a { 
            display: inline-block; 
            padding: 10px 20px; 
            border-radius: 999px; 
            background: var(--muted); 
            color: white; 
            text-decoration: none; 
            font-weight: 600; 
        }
        .footer-link a:hover { opacity: 0.9; }
    </style>
</head>
<body>

<div class="header">
    <h1>➕ Créer</h1>
    <a href="/exams3-main/exams3/tableau-bord">← Retour</a>
</div>

<div class="container">
    <div class="grid">

        <!-- REGION -->
        <div class="card">
            <h2>🗺️ Ajouter une Région</h2>
            <form method="post" action="/exams3-main/exams3/regions">
                <div class="form-group">
                    <label for="region_nom">Nom de la région</label>
                    <input id="region_nom" type="text" name="nom" placeholder="Ex: Analamanga">
                </div>
                <button class="btn" type="submit">Créer une région</button>
            </form>
        </div>

        <!-- VILLE -->
        <div class="card">
            <h2>🏘️ Ajouter une Ville</h2>
            <form method="post" action="/exams3-main/exams3/villes">
                <div class="form-group">
                    <label for="ville_nom">Nom de la ville</label>
                    <input id="ville_nom" type="text" name="nom" placeholder="Ex: Antananarivo">
                </div>
                <div class="form-group">
                    <label for="ville_region">Région</label>
                    <?php if (!empty($regions) && is_array($regions)): ?>
                        <select id="ville_region" name="id_regions">
                            <?php foreach ($regions as $r): ?>
                                <option value="<?php echo htmlspecialchars($r['id']); ?>"><?php echo htmlspecialchars($r['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input id="ville_region" type="number" name="id_regions" placeholder="Ex: 1" min="1">
                    <?php endif; ?>
                </div>
                <button class="btn" type="submit">Créer une ville</button>
            </form>
        </div>

        <!-- BESOIN -->
        <div class="card">
            <h2>📦 Ajouter un Besoin</h2>
            <form method="post" action="/exams3-main/exams3/besoins">
                <div class="form-group">
                    <label for="besoin_nom">Type de besoin</label>
                    <input id="besoin_nom" type="text" name="nom" placeholder="Ex: Riz, Eau, Médicaments">
                </div>
                <div class="form-group">
                    <label for="besoin_nombre">Quantité</label>
                    <input id="besoin_nombre" type="number" name="nombre" placeholder="0.00" step="0.01">
                </div>
                <div class="form-group">
                    <label for="besoin_ville">Ville</label>
                    <?php if (!empty($villes) && is_array($villes)): ?>
                        <select id="besoin_ville" name="id_ville">
                            <?php foreach ($villes as $v): ?>
                                <option value="<?php echo htmlspecialchars($v['id']); ?>"><?php echo htmlspecialchars($v['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input id="besoin_ville" type="number" name="id_ville" placeholder="Ex: 1" min="1">
                    <?php endif; ?>
                </div>
                <button class="btn" type="submit">Créer un besoin</button>
            </form>
        </div>

    </div>

    <!-- Nouveau lien en bas -->
    <div class="footer-link">
        <a href="/exams3-main/exams3/tableau-bord">← aller au tableau de bord</a>
    </div>
</div>

</body>
</html>
