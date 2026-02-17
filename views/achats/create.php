<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel Achat - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --success: #28a745; --danger: #dc3545; }
        * { box-sizing: border-box; }
        body {
            font-family: Inter, Segoe UI, Arial, sans-serif;
            background: var(--bg);
            margin: 0;
            padding: 0;
        }
        .header {
            background: var(--brand);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; }

        nav {
            background: white;
            padding: 10px 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            flex-wrap: wrap;
        }
        nav a {
            color: var(--brand);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            background: rgba(19,38,92,0.08);
            transition: all 0.3s;
        }
        nav a:hover, nav a.active {
            background: var(--brand);
            color: white;
        }

        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, var(--brand), #1a2f7a);
            color: white;
            padding: 20px;
        }
        .card-header h2 {
            margin: 0;
            font-size: 20px;
        }
        .card-body { padding: 25px; }

        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 14px;
        }
        select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
            font-size: 16px;
        }
        select:focus, input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(19,38,92,0.1);
        }

        .montant-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed var(--brand);
        }
        .montant-label {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 5px;
        }
        .montant-valeur {
            font-size: 32px;
            font-weight: bold;
            color: var(--brand);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 999px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: var(--brand); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-secondary { background: #6b7280; color: white; }

        .flex-row {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            margin-top: 20px;
        }

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
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Nouvel Achat</h1>
        <p>Enregistrer un achat pour répondre aux besoins</p>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/achats" class="active">Achats</a>
        <a href="<?= $base ?>/logout">Déconnexion</a>
    </nav>

    <div class="container">
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
            <div class="alert alert-error">
                ❌ Erreur : Tous les champs doivent être remplis correctement.
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>➕ Nouvel achat</h2>
            </div>
            <div class="card-body">
                
                <form method="POST" action="<?= $base ?>/achats/create" id="achatForm">
                    
                    <div class="form-group">
                        <label for="besoin">Besoin à satisfaire</label>
                        <select name="id_besoin" id="besoin" required>
                            <option value="">-- Sélectionnez un besoin --</option>
                            <?php foreach ($besoins as $besoin): ?>
                                <option value="<?= $besoin['id'] ?>" 
                                        data-prix="<?= $besoin['prix_unitaire'] ?? 0 ?>"
                                        data-ville="<?= htmlspecialchars($besoin['ville_nom']) ?>">
                                    <?= htmlspecialchars($besoin['ville_nom']) ?> - 
                                    <?= htmlspecialchars($besoin['nom']) ?> 
                                    (<?= number_format($besoin['nombre'], 0, ',', ' ') ?> restants)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantite">Quantité à acheter</label>
                        <input type="number" name="quantite" id="quantite" min="1" step="1" required>
                    </div>

                    <div class="form-group">
                        <label for="prix_unitaire">Prix unitaire (Ar)</label>
                        <input type="number" name="prix_unitaire" id="prix_unitaire" min="1" step="100" required>
                    </div>

                    <div class="montant-box">
                        <div class="montant-label">Montant total</div>
                        <div class="montant-valeur" id="montantTotal">0 Ar</div>
                    </div>

                    <div class="flex-row">
                        <a href="<?= $base ?>/achats" class="btn btn-secondary">← Annuler</a>
                        <button type="submit" class="btn btn-success">✅ Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const besoinSelect = document.getElementById('besoin');
        const quantiteInput = document.getElementById('quantite');
        const prixInput = document.getElementById('prix_unitaire');
        const montantSpan = document.getElementById('montantTotal');

        function calculerMontant() {
            const quantite = parseFloat(quantiteInput.value) || 0;
            const prix = parseFloat(prixInput.value) || 0;
            
            if (quantite > 0 && prix > 0) {
                const montant = quantite * prix;
                montantSpan.textContent = montant.toLocaleString('fr-FR') + ' Ar';
            } else {
                montantSpan.textContent = '0 Ar';
            }
        }

        quantiteInput.addEventListener('input', calculerMontant);
        prixInput.addEventListener('input', calculerMontant);

        besoinSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const prix = selected.dataset.prix;
            if (prix && prix > 0) {
                prixInput.value = prix;
                calculerMontant();
            }
        });
    </script>
</body>
</html>