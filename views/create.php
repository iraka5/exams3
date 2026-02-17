<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer - Admin BNGRC</title>
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
            <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Gestion des Données</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Créer et configurer les éléments du système</p>
        </div>

        <!-- Navigation avec boutons arrondis individuels -->
        <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
            <a href="/exams3-main/exams3/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
            <a href="/exams3-main/exams3/regions" class="btn btn-outline" style="border-radius: 25px;">Régions</a>
            <a href="/exams3-main/exams3/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
            <a href="/exams3-main/exams3/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
            <a href="/exams3-main/exams3/dons" class="btn btn-outline" style="border-radius: 25px;">Dons</a>
            <a href="/exams3-main/exams3/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
            <a href="/exams3-main/exams3/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
        </nav>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        <!-- REGION -->
        <div class="card" style="transition: all 0.3s ease;">
            <h2 style="color: var(--accent-blue); margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600;">Nouvelle Région</h2>
            <form method="post" action="/exams3-main/exams3/regions">
                <div class="form-group">
                    <label for="region_nom">Nom de la région</label>
                    <input id="region_nom" type="text" name="nom" placeholder="Ex: Analamanga">
                </div>
                <button class="btn btn-success" type="submit">Créer la région</button>
            </form>
        </div>

        <!-- VILLE -->
        <div class="card" style="transition: all 0.3s ease;">
            <h2 style="color: var(--accent-blue); margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600;">Nouvelle Ville</h2>
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
                <button class="btn btn-success" type="submit">Créer la ville</button>
            </form>
        </div>

        <!-- BESOIN -->
        <div class="card" style="transition: all 0.3s ease;">
            <h2 style="color: var(--accent-blue); margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600;">Nouveau Besoin</h2>
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
                <button class="btn btn-success" type="submit">Créer le besoin</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
