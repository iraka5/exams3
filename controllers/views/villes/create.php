<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Ville - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { background-color: #333; padding: 10px; margin-bottom: 20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 3px; border: none; cursor: pointer; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }
        .error { color: red; margin-bottom: 15px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
    </style>
</head>
<body>

<nav>
    <a href="/regions">Régions</a>
    <a href="/villes">Villes</a>
    <a href="/besoins">Besoins</a>
    <a href="/dons">Dons</a>
    <a href="/tableau-bord">Tableau de Bord</a>
</nav>

<h1>➕ Ajouter une Nouvelle Ville</h1>

<div class="card">
    <?php if (isset($_GET["error"])): ?>
        <div class="error">
            ⚠️ Erreur : Tous les champs sont requis !
        </div>
    <?php endif; ?>

    <form method="POST" action="/villes/store">
        <div class="form-group">
            <label for="nom">Nom de la Ville :</label>
            <input type="text" 
                   id="nom" 
                   name="nom" 
                   placeholder="Ex: Antananarivo, Antsirabe..." 
                   required>
        </div>

        <div class="form-group">
            <label for="id_regions">Région :</label>
            <select id="id_regions" name="id_regions" required>
                <option value="">-- Sélectionner une région --</option>
                <?php foreach ($regions as $region): ?>
                    <option value="<?= $region["id"] ?>">
                        <?= htmlspecialchars($region["nom"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-success">💾 Enregistrer</button>
            <a href="/villes" class="btn btn-secondary">❌ Annuler</a>
        </div>
    </form>
</div>

</body>
</html>