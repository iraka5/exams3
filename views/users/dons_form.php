<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire un Don - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .header { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .nav { background-color: #2c3e50; padding: 15px; display: flex; align-items: center; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; padding: 8px 15px; border-radius: 4px; }
        .nav a:hover, .nav a.active { background-color: #34495e; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .info-box { background: linear-gradient(135deg, #e8f8f5, #d5f4e6); border: 1px solid #27ae60; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .info-box h3 { color: #27ae60; margin: 0 0 10px 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #27ae60; box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1); }
        .btn { padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; text-decoration: none; display: inline-block; text-align: center; transition: all 0.3s; }
        .btn-success { background: #27ae60; color: white; }
        .btn-success:hover { background: #229954; transform: translateY(-2px); }
        .btn-secondary { background: #95a5a6; color: white; }
        .btn-secondary:hover { background: #7f8c8d; }
        .btn-group { display: flex; gap: 15px; margin-top: 25px; flex-wrap: wrap; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .back-btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; }
        .back-btn:hover { background: #2980b9; }
        .suggestions { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px; }
        .suggestions h4 { margin: 0 0 10px 0; color: #2c3e50; font-size: 14px; }
        .suggestions .suggestion { display: inline-block; background: white; border: 1px solid #ddd; padding: 5px 10px; margin: 3px; border-radius: 15px; cursor: pointer; font-size: 12px; transition: all 0.3s; }
        .suggestions .suggestion:hover { background: #27ae60; color: white; }
        .required { color: #e74c3c; }
    </style>
    <script>
        function selectSuggestion(element, inputId) {
            document.getElementById(inputId).value = element.textContent;
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>🎁 Faire un Don</h1>
        <p>Soutenez les sinistrés avec votre générosité</p>
    </div>

    <div class="nav">
        <a href="/exams3-main/exams3/user/dashboard">🏠 Accueil</a>
        <a href="/exams3-main/exams3/user/besoins">📋 Besoins</a>
        <a href="/exams3-main/exams3/user/dons" class="active">🎁 Faire un Don</a>
        <a href="/exams3-main/exams3/user/villes">📊 Statistiques Villes</a>
        <a href="/exams3-main/exams3/user/logout" style="margin-left: auto;">🚪 Déconnexion</a>
    </div>

    <div class="container">
        <a href="/exams3-main/exams3/user/dashboard" class="back-btn">← Retour au tableau de bord</a>
        
        <div class="form-container">
            <div class="info-box">
                <h3>❤️ Merci pour votre générosité !</h3>
                <p>Votre don contribuera directement à améliorer les conditions de vie des personnes touchées par les catastrophes naturelles à Madagascar. Chaque don compte, quelle que soit sa taille.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <strong>❌ Erreur :</strong> Veuillez remplir tous les champs correctement.
                </div>
            <?php endif; ?>

            <form method="POST" action="/exams3-main/exams3/user/dons">
                <div class="form-group">
                    <label for="nom_donneur">👤 Nom du donneur <span class="required">*</span></label>
                    <input type="text" id="nom_donneur" name="nom_donneur" required 
                           placeholder="Votre nom complet ou nom de votre organisation"
                           value="<?= $_SESSION['user_name'] ?? '' ?>">
                    <small style="color: #7f8c8d;">Ce nom apparaîtra dans les registres de dons</small>
                </div>

                <div class="form-group">
                    <label for="type_don">🎁 Type de don <span class="required">*</span></label>
                    <input type="text" id="type_don" name="type_don" required 
                           placeholder="Ex: Riz, Huile, Vêtements, Argent...">
                    <div class="suggestions">
                        <h4>💡 Suggestions de dons couramment nécessaires :</h4>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Riz</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Huile alimentaire</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Eau potable</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Vêtements</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Couvertures</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Médicaments</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Tôles</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Argent</span>
                        <span class="suggestion" onclick="selectSuggestion(this, 'type_don')">Matériel scolaire</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre_don">📊 Quantité ou montant <span class="required">*</span></label>
                    <input type="number" id="nombre_don" name="nombre_don" step="0.01" min="0.01" required 
                           placeholder="Ex: 50 (kg), 1000000 (Ar), 10 (pièces)...">
                    <small style="color: #7f8c8d;">
                        Pour l'argent, indiquez le montant en Ariary. Pour les biens, indiquez la quantité (kg, pièces, litres, etc.)
                    </small>
                </div>

                <div class="form-group">
                    <label for="id_ville">🏙️ Ville de destination <span class="required">*</span></label>
                    <select id="id_ville" name="id_ville" required>
                        <option value="">-- Sélectionnez la ville où envoyer le don --</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id'] ?>">
                                📍 <?= htmlspecialchars($ville['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #7f8c8d;">Choisissez la ville où vous souhaitez que votre don soit distribué</small>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Confirmer le Don
                    </button>
                    <a href="/exams3-main/exams3/user/dashboard" class="btn btn-secondary">
                        ❌ Annuler
                    </a>
                </div>
            </form>

            <div style="background: #e8f4f8; padding: 20px; border-radius: 8px; margin-top: 25px; text-align: center;">
                <h4 style="color: #2c3e50; margin: 0 0 10px 0;">📋 Besoin d'inspiration ?</h4>
                <p style="margin: 0; color: #7f8c8d;">Consultez les besoins actuels pour voir où votre aide sera la plus utile.</p>
                <a href="/exams3-main/exams3/user/besoins" class="btn" style="background: #3498db; color: white; margin-top: 10px; font-size: 14px; padding: 10px 20px;">
                    👀 Voir les Besoins Actuels
                </a>
            </div>
        </div>
    </div>
</body>
</html>