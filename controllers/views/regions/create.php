<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Région - BNGRC</title>
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
            <a href="/">← Accueil</a>
            <a href="/regions">Liste des régions</a>
        </div>

        <h1>Ajouter une Nouvelle Région</h1>
            
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert">
                Le nom de la région est obligatoire.
            </div>
        <?php endif; ?>

        <form action="/regions" method="POST">
            <div class="form-group">
                <label for="nom">Nom de la région *</label>
                <input type="text" id="nom" name="nom" required maxlength="100" 
                       placeholder="Ex: Analamanga, Vakinankaratra...">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Ajouter la région</button>
                <a href="/regions" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>