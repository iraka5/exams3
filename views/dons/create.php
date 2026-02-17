<?php
$base = '/exams3-main/exams3';

// Initialisation de la variable $villes pour éviter les erreurs
if (!isset($villes)) $villes = [];
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
        nav { background: white; padding: 10px; display: flex; gap: 10px; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav a { color: var(--brand); text-decoration: none; padding: 8px 15px; border-radius: 20px; }
        nav a:hover { background: var(--brand); color: white; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { color: var(--brand); margin-top: 0; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: var(--muted); }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus, select:focus {
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
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Enregistrer un don</h1>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
    </nav>

    <div class="container">
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 'depassement'): ?>
            <div class="alert alert-error">
                <strong>❌ Erreur :</strong> La quantité de don dépasse les besoins restants pour cette ville.
                <?php if (isset($_GET['max'])): ?>
                    <br>Quantité maximale autorisée : <strong><?= htmlspecialchars($_GET['max']) ?></strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 'plus_besoin'): ?>
            <div class="alert alert-warning">
                <strong>⚠️ Attention :</strong> Tous les besoins de cette ville sont déjà satisfaits. Aucun don supplémentaire n'est nécessaire.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
            <div class="alert alert-error">
                <strong>❌ Erreur :</strong> Tous les champs doivent être remplis correctement.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <strong>✅ Succès :</strong> Don enregistré avec succès !
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Nouveau don</h2>
            
            <form method="post" action="<?= $base ?>/dons/create">
                <div class="form-group">
                    <label for="nom_donneur">Nom du donneur</label>
                    <input type="text" id="nom_donneur" name="nom_donneur" placeholder="Ex: Jean Dupont, Entreprise ABC..." required>
                </div>

                <div class="form-group">
                    <label for="type_don">Type de don</label>
                    <select id="type_don" name="type_don" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <option value="nature">Nature</option>
                        <option value="materiaux">Matériaux</option>
                        <option value="argent">Argent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre_don">Quantité</label>
                    <input type="number" id="nombre_don" name="nombre_don" min="1" step="1" placeholder="Ex: 500" required>
                </div>

                <div class="form-group">
                    <label for="id_ville">Ville bénéficiaire</label>
                    <select id="id_ville" name="id_ville" required>
                        <option value="">-- Sélectionnez une ville --</option>
                        <?php if (!empty($villes)): ?>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Aucune ville disponible</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="actions">
                    <a href="<?= $base ?>/dons" class="btn btn-back">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer le don</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>