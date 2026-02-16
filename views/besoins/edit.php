<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Besoin - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        .nav { margin-bottom: 30px; }
        .nav a { color: #3498db; text-decoration: none; margin-right: 15px; }
        .nav a:hover { text-decoration: underline; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #95a5a6; color: white; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="/exams3-main/exams3/">← Accueil</a>
            <a href="/exams3-main/exams3/besoins">Liste des besoins</a>
            <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>">Voir ce besoin</a>
        </div>

        <h1>Modifier le Besoin #<?= $besoin['id'] ?></h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">
                Veuillez remplir tous les champs correctement.
            </div>
        <?php endif; ?>

        <form method="POST" action="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>">
            <div class="form-group">
                <label for="nom">Nom du besoin *</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($besoin['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nombre">Quantité nécessaire *</label>
                <input type="number" id="nombre" name="nombre" value="<?= $besoin['nombre'] ?>" step="0.01" min="0.01" required>
            </div>

            <div class="form-group">
                <label for="id_ville">Ville concernée *</label>
                <select id="id_ville" name="id_ville" required>
                    <option value="">-- Sélectionnez une ville --</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= $ville['id'] ?>" <?= ($ville['id'] == $besoin['id_ville']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ville['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>