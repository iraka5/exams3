<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Achat - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 999px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .info-box { background: linear-gradient(135deg, #e8f8f5, #d5f4e6); border: 1px solid #27ae60; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .info-box h3 { color: #27ae60; margin: 0 0 10px 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; font-size: 14px; }
        select, input { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 12px; box-sizing: border-box; font-size: 14px; transition: border-color 0.3s; }
        select:focus, input:focus { outline: none; border-color: #27ae60; box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1); }
        .btn { padding: 15px 30px; border: none; border-radius: 999px; cursor: pointer; font-size: 16px; font-weight: 600; text-decoration: none; display: inline-block; text-align: center; transition: all 0.3s; }
        .btn-success { background: #27ae60; color: white; }
        .btn-success:hover { background: #229954; transform: translateY(-2px); }
        .btn-secondary { background: #95a5a6; color: white; }
        .btn-secondary:hover { background: #7f8c8d; }
        .btn-group { display: flex; gap: 15px; margin-top: 25px; flex-wrap: wrap; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 12px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .back-btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 999px; display: inline-block; margin-bottom: 20px; }
        .back-btn:hover { background: #2980b9; }
        .calculation-box { background: #f8f9fa; border: 2px dashed #3498db; border-radius: 8px; padding: 15px; margin-top: 10px; }
        .calculation-box h4 { color: #3498db; margin: 0 0 10px 0; }
        .calculation-result { font-size: 18px; font-weight: bold; color: #27ae60; }
        .required { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>➕ Nouvel Achat</h1>
        <p>Effectuer un achat avec les dons en argent</p>
    </div>

    <div class="nav">
        <a href="/exams3-main/exams3/tableau-bord">🏠 Accueil</a>
        <a href="/exams3-main/exams3/achats">📝 Achats</a>
        <a href="/exams3-main/exams3/achats/create" class="active">➕ Nouvel Achat</a>
        <a href="/exams3-main/exams3/achats/recapitulatif">📊 Récapitulatif</a>
        <a href="/exams3-main/exams3/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </div>

    <div class="container">
        <a href="/exams3-main/exams3/achats" class="back-btn">← Retour à la liste</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="info-box">
                <h3>💡 Information</h3>
                <p>Les achats sont effectués automatiquement en utilisant les dons en argent disponibles. Le montant sera calculé automatiquement selon le prix unitaire du besoin sélectionné.</p>
            </div>

            <form method="POST" action="/exams3-main/exams3/achats" id="achatForm">
                <div class="form-group">
                    <label for="id_ville">Ville <span class="required">*</span></label>
                    <select name="id_ville" id="id_ville" required>
                        <option value="">-- Sélectionner une ville --</option>
                        <?php if (isset($villes)): ?>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>">
                                    <?= htmlspecialchars($ville['region_nom'] . ' - ' . $ville['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_besoin">Besoin à acheter <span class="required">*</span></label>
                    <select name="id_besoin" id="id_besoin" required disabled>
                        <option value="">-- Sélectionner d'abord une ville --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantite">Quantité <span class="required">*</span></label>
                    <input type="number" name="quantite" id="quantite" step="0.01" min="0.01" placeholder="Quantité à acheter" required>
                </div>

                <div class="calculation-box" id="calculationBox" style="display: none;">
                    <h4>🧮 Calcul automatique</h4>
                    <div id="calculationDetails"></div>
                    <div class="calculation-result" id="calculationResult"></div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">💰 Effectuer l'achat</button>
                    <a href="/exams3-main/exams3/achats" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('id_ville').addEventListener('change', function() {
            const villeId = this.value;
            const besoinSelect = document.getElementById('id_besoin');
            
            if (villeId) {
                fetch(`/exams3-main/exams3/api/achats/besoins/${villeId}`)
                    .then(response => response.json())
                    .then(besoins => {
                        besoinSelect.innerHTML = '<option value="">-- Sélectionner un besoin --</option>';
                        besoins.forEach(besoin => {
                            besoinSelect.innerHTML += `<option value="${besoin.id}" data-prix="${besoin.prix_unitaire}">${besoin.nom} (${new Intl.NumberFormat('fr-FR').format(besoin.prix_unitaire)} Ar)</option>`;
                        });
                        besoinSelect.disabled = false;
                    })
                    .catch(error => console.error('Erreur:', error));
            } else {
                besoinSelect.innerHTML = '<option value="">-- Sélectionner d\'abord une ville --</option>';
                besoinSelect.disabled = true;
            }
            calculateTotal();
        });

        document.getElementById('id_besoin').addEventListener('change', calculateTotal);
        document.getElementById('quantite').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const besoinSelect = document.getElementById('id_besoin');
            const quantiteInput = document.getElementById('quantite');
            const calculationBox = document.getElementById('calculationBox');
            const calculationDetails = document.getElementById('calculationDetails');
            const calculationResult = document.getElementById('calculationResult');

            if (besoinSelect.value && quantiteInput.value && quantiteInput.value > 0) {
                const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
                const prixUnitaire = parseFloat(selectedOption.dataset.prix);
                const quantite = parseFloat(quantiteInput.value);
                const montantTotal = prixUnitaire * quantite;

                calculationDetails.innerHTML = `
                    <strong>Besoin:</strong> ${selectedOption.textContent}<br>
                    <strong>Prix unitaire:</strong> ${new Intl.NumberFormat('fr-FR').format(prixUnitaire)} Ar<br>
                    <strong>Quantité:</strong> ${quantite}<br>
                    <strong>Calcul:</strong> ${quantite} × ${new Intl.NumberFormat('fr-FR').format(prixUnitaire)} Ar
                `;
                calculationResult.textContent = `Montant total: ${new Intl.NumberFormat('fr-FR').format(montantTotal)} Ar`;
                calculationBox.style.display = 'block';
            } else {
                calculationBox.style.display = 'none';
            }
        }
    </script>
</body>
</html>