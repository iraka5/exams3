<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Don - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
        body { font-family: Arial, sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .header { background: var(--brand); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        nav { background: white; padding: 10px; display: flex; gap: 10px; justify-content: center; }
        nav a { color: var(--brand); text-decoration: none; padding: 8px 15px; border-radius: 20px; }
        nav a:hover { background: var(--brand); color: white; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { color: var(--brand); margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: var(--muted); }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 20px;
            border: none;
            background: var(--brand);
            color: white;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover { opacity: 0.9; }
        .btn-success { background: #28a745; }
        .btn-back { background: #6b7280; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Enregistrement d'un don</h1>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/create">➕ Créer</a>
    </nav>

    <div class="container">
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

                <div>
                    <a href="<?= $base ?>/dons" class="btn btn-back">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer le don</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>