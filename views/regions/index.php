<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régions - BNGRC</title>
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
            <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">BNGRC - Gestion des Régions</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Administration des régions de Madagascar</p>
        </div>

        <!-- Navigation avec boutons arrondis individuels -->
        <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
            <a href="<?= $base ?>/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
            <a href="<?= $base ?>/regions" class="btn btn-primary" style="border-radius: 25px;">Régions</a>
            <a href="<?= $base ?>/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
            <a href="<?= $base ?>/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
            <a href="<?= $base ?>/dons" class="btn btn-outline" style="border-radius: 25px;">Dons</a>
            <a href="<?= $base ?>/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
            <a href="<?= $base ?>/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
        </nav>

        <!-- Contenu principal -->
        <div class="header-actions">
            <h2>Liste des régions</h2>
            <a href="<?= $base ?>/regions/create" class="btn btn-success">➕ Nouvelle région</a>
        </div>

        <?php if (empty($regions)): ?>
            <p style="text-align: center; padding: 40px; background: white; border-radius: 8px;">
                Aucune région enregistrée. <a href="<?= $base ?>/regions/create">Ajouter une région</a>
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regions as $region): ?>
                    <tr>
                        <td><?= $region['id'] ?></td>
                        <td><strong><?= htmlspecialchars($region['nom']) ?></strong></td>
                        <td class="actions">
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>" class="btn btn-small">Voir</a>
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>/edit" class="btn btn-small btn-warning">Modifier</a>
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>/delete" class="btn btn-small btn-danger" onclick="return confirm('Supprimer cette région ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>