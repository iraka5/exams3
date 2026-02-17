<?php
$base = '/exams3-main/exams3';
?>
<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Don - BNGRC</title>
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
            <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">BNGRC - Enregistrement d'un don</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Ajouter un nouveaux don au système</p>
        </div>

        <!-- Navigation avec boutons arrondis individuels -->
        <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
            <a href="<?= $base ?>/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
            <a href="<?= $base ?>/regions" class="btn btn-outline" style="border-radius: 25px;">Régions</a>
            <a href="<?= $base ?>/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
            <a href="<?= $base ?>/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
            <a href="<?= $base ?>/dons" class="btn btn-primary" style="border-radius: 25px;">Dons</a>
            <a href="<?= $base ?>/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
            <a href="<?= $base ?>/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
        </nav>

        <!-- Contenu principal -->
        <div class="card">
            <h2>Enregistrer un nouveau don</h2>
            
            <form method="post" action="<?= $base ?>/dons">
                <div class="form-group">
                    <label for="nom_donneur">Nom du donneur</label>
                    <input type="text" id="nom_donneur" name="nom_donneur" required>
                </div>

                <div class="form-group">
                    <label for="type_don">Type de don</label>
                    <select id="type_don" name="type_don" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="nature">Nature</option>
                        <option value="materiaux">Matériaux</option>
                        <option value="argent">Argent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre_don">Quantité</label>
                    <input type="number" id="nombre_don" name="nombre_don" min="1" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="id_ville">Ville</label>
                    <select id="id_ville" name="id_ville" required>
                        <option value="">Sélectionnez une ville</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id'] ?>" <?= (isset($_GET['ville_id']) && $_GET['ville_id'] == $ville['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="actions" style="display: flex; gap: 1rem; justify-content: space-between; margin-top: 2rem;">
                    <a href="<?= $base ?>/dons" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer le don</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>