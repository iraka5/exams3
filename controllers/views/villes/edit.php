<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la ville - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; margin: -20px -20px 20px -20px; }
        nav { background: #34495e; padding: 10px; margin: 20px -20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; }
        nav a.active, nav a:hover { background: #2c3e50; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-container { background: white; padding: 30px; border-radius: 5px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 3px; color: white; border: none; cursor: pointer; margin-right: 10px; }
        .btn-primary { background: #3498db; }
        .btn-secondary { background: #95a5a6; }
        .error { background: #e74c3c; color: white; padding: 10px; border-radius: 3px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Modifier la ville</h1>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions">Régions</a>
        <a href="/exams3-main/exams3/villes" class="active">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Modifier: <?= htmlspecialchars($ville['nom']) ?></h2>
            
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="error">Tous les champs sont obligatoires.</div>
            <?php endif; ?>

            <form action="/exams3-main/exams3/villes/<?= $ville['id'] ?>/update" method="POST">
                <div class="form-group">
                    <label for="nom">Nom de la ville *</label>
                    <input type="text" id="nom" name="nom" required maxlength="100" 
                           value="<?= htmlspecialchars($ville['nom']) ?>">
                </div>

                <div class="form-group">
                    <label for="id_regions">Région *</label>
                    <select id="id_regions" name="id_regions" required>
                        <option value="">Sélectionner une région</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= $region['id'] ?>" <?= ($ville['id_regions'] == $region['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($region['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="/exams3-main/exams3/villes" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>