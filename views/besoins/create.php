<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Besoin - BNGRC</title>
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
        </div>

        <h1>Ajouter un Nouveau Besoin</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">
                Veuillez remplir tous les champs correctement.
            </div>
        <?php endif; ?>

        <form method="POST" action="/exams3-main/exams3/besoins">
            <div class="form-group">
                <label for="nom">Nom du besoin *</label>
                <input type="text" id="nom" name="nom" required placeholder="Ex: Riz, Eau potable, Vêtements...">
            </div>

            <div class="form-group">
                <label for="nombre">Quantité nécessaire *</label>
                <input type="number" id="nombre" name="nombre" step="0.01" min="0.01" required placeholder="Ex: 100">
            </div>

            <div class="form-group">
                <label for="prix_unitaire">Prix unitaire (Ar) *</label>
                <input type="number" id="prix_unitaire" name="prix_unitaire" step="0.01" min="0.01" required placeholder="Ex: 1500">
            </div>

            <div class="form-group">
                <label for="type_besoin">Type de besoin *</label>
                <select id="type_besoin" name="type_besoin" required>
                    <option value="">-- Sélectionnez un type --</option>
                    <option value="nature">Nature (nourriture, eau, etc.)</option>
                    <option value="materiaux">Matériaux (construction, outils, etc.)</option>
                    <option value="argent">Argent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="id_ville">Ville concernée *</label>
                <select id="id_ville" name="id_ville" required>
                    <option value="">-- Sélectionnez une ville --</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Enregistrer le Besoin</button>
                <a href="/exams3-main/exams3/besoins" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>