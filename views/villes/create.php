<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Ville - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
        body { font-family: Arial, sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .header { background: var(--brand); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        nav { background: white; padding: 10px; display: flex; gap: 10px; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav a { color: var(--brand); text-decoration: none; padding: 8px 15px; border-radius: 20px; }
        nav a:hover { background: var(--brand); color: white; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { color: var(--brand); margin-top: 0; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: var(--muted); }
        input[type="text"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(19,38,92,0.1);
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
            font-size: 16px;
            margin-right: 10px;
        }
        .btn:hover { opacity: 0.9; }
        .btn-success { background: #28a745; }
        .btn-back { background: #6b7280; }
        .actions { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Ajouter une ville</h1>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Nouvelle ville</h2>
            
            <form method="post" action="<?= $base ?>/villes/create">
                <div class="form-group">
                    <label for="nom">Nom de la ville</label>
                    <input type="text" id="nom" name="nom" placeholder="Ex: Analamanga, Atsinanana..." required value="<?= htmlspecialchars($nom ?? '') ?>">

                </div>

                <div class="form-group">
                    <label for="id_regions">Région</label>
                    <select id="id_regions" name="id_regions" required>
                        <option value="">-- Sélectionnez une région --</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= $region['id'] ?>"><?= htmlspecialchars($region['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="actions">
                    <a href="<?= $base ?>/villes" class="btn btn-back">Annuler</a>
                    <button type="submit" class="btn btn-success">Créer la ville</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>