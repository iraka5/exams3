<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendre un don - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --success: #28a745; --info: #17a2b8; --warning: #ffc107; --danger: #dc3545; }
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

        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e6e9ef;
        }
        .card-header.primary {
            background: linear-gradient(135deg, var(--brand), #1a2f7a);
            color: white;
        }
        .card-header.info {
            background: var(--info);
            color: white;
        }
        .card-header.warning {
            background: var(--warning);
            color: #212529;
        }
        .card-header h4 {
            margin: 0 0 10px 0;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-header p {
            margin: 0;
            opacity: 0.9;
        }
        .card-body { padding: 25px; }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .info-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            font-weight: bold;
            color: var(--brand);
        }

        .prix-box {
            background: linear-gradient(135deg, var(--brand), #1a2f7a);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
        }
        .prix-original {
            font-size: 16px;
            opacity: 0.9;
            text-decoration: line-through;
        }
        .prix-reduit {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        .prix-taux {
            font-size: 14px;
            opacity: 0.9;
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
        .btn-danger { background: var(--danger); color: white; }
        .btn-secondary { background: var(--muted); color: white; }

        .flex-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .progress {
            height: 20px;
            background: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
        }
        .progress-bar.bg-success { background: var(--success); }
        .progress-bar.bg-warning { background: var(--warning); }

        @media (max-width: 768px) {
            .info-grid { grid-template-columns: 1fr; }
            .flex-row { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Vente d'article</h1>
        <p>Convertir un don en argent</p>
    </div>

    <nav>
        <a href="<?= $base ?>/">🏠 Accueil</a>
        <a href="<?= $base ?>/regions">🗺️ Régions</a>
        <a href="<?= $base ?>/villes">🏘️ Villes</a>
        <a href="<?= $base ?>/besoins">📋 Besoins</a>
        <a href="<?= $base ?>/dons">🎁 Dons</a>
        <a href="<?= $base ?>/ventes">💰 Ventes</a>
        <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
        <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
    </nav>

    <div class="container">
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <span style="font-size: 20px;">❌</span>
                <div>
                    <strong>Erreur :</strong> <?= htmlspecialchars(urldecode($raison)) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header primary">
                <h4>
                    <span style="font-size: 24px;">💰</span>
                    Vendre un article
                </h4>
                <p>Détails de l'article à vendre</p>
            </div>
            <div class="card-body">
                
                <!-- Informations de l'article -->
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Type</div>
                        <div class="info-value"><?= htmlspecialchars($don['type_don']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Donneur</div>
                        <div class="info-value"><?= htmlspecialchars($don['nom_donneur']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Quantité</div>
                        <div class="info-value"><?= number_format($don['nombre_don'], 0, ',', ' ') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Ville</div>
                        <div class="info-value"><?= htmlspecialchars($don['ville_nom']) ?></div>
                    </div>
                </div>

                <!-- Vérification de vendabilité (via JavaScript) -->
                <div id="vendabilite-check" class="alert alert-info">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    <span>Vérification de la vendabilité...</span>
                </div>

                <div id="vendabilite-result" style="display: none;"></div>

                <!-- Calcul du prix -->
                <div id="prix-calcul" class="prix-box" style="display: none;">
                    <div class="prix-original">Prix original : <?= number_format($prix_original, 0, ',', ' ') ?> Ar</div>
                    <div class="prix-reduit" id="prix-final"><?= number_format($prix_vente, 0, ',', ' ') ?> Ar</div>
                    <div class="prix-taux">Taux de diminution : <?= $taux_diminution ?>%</div>
                </div>

                <div class="progress" id="progress-bar" style="display: none;">
                    <div class="progress-bar bg-success" style="width: <?= 100 - $taux_diminution ?>%"><?= 100 - $taux_diminution ?>%</div>
                    <div class="progress-bar bg-warning" style="width: <?= $taux_diminution ?>%">-<?= $taux_diminution ?>%</div>
                </div>

                <!-- Formulaire de vente -->
                <form method="POST" id="vente-form" style="display: none;">
                    <input type="hidden" name="id_don" value="<?= $don['id'] ?>">
                    
                    <div class="alert alert-info">
                        <span style="font-size: 20px;">💡</span>
                        <div>
                            <strong>Confirmation :</strong><br>
                            L'article sera marqué comme vendu et <?= number_format($prix_vente, 0, ',', ' ') ?> Ar seront ajoutés aux fonds disponibles.
                        </div>
                    </div>

                    <div class="flex-row">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Confirmer la vente de cet article ?')">
                            💰 Confirmer la vente
                        </button>
                        <a href="<?= $base ?>/dons" class="btn btn-secondary">
                            ← Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        verifierVendabilite();
    });

    function verifierVendabilite() {
        const donId = <?= $don['id'] ?>;
        
        fetch(`<?= $base ?>/api/dons/${donId}/vendable`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('vendabilite-check').style.display = 'none';
                document.getElementById('vendabilite-result').style.display = 'block';
                
                const resultDiv = document.getElementById('vendabilite-result');
                
                if (data.vendable) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <span style="font-size: 20px;">✅</span>
                            <div>
                                <strong>Article vendable</strong><br>
                                ${data.raison}
                            </div>
                        </div>
                    `;
                    
                    // Afficher le prix et le formulaire
                    document.getElementById('prix-calcul').style.display = 'block';
                    document.getElementById('progress-bar').style.display = 'flex';
                    document.getElementById('vente-form').style.display = 'block';
                    
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-warning">
                            <span style="font-size: 20px;">⚠️</span>
                            <div>
                                <strong>Article non vendable</strong><br>
                                ${data.raison}
                                ${data.besoin_restant ? `<br>Besoins restants : ${data.besoin_restant} unités` : ''}
                            </div>
                        </div>
                    `;
                    
                    // Ajouter un bouton retour
                    resultDiv.innerHTML += `
                        <div style="margin-top: 15px;">
                            <a href="<?= $base ?>/dons" class="btn btn-secondary">
                                ← Retour aux dons
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('vendabilite-check').style.display = 'none';
                document.getElementById('vendabilite-result').innerHTML = `
                    <div class="alert alert-danger">
                        <span style="font-size: 20px;">❌</span>
                        <div>
                            <strong>Erreur technique</strong><br>
                            Impossible de vérifier la vendabilité.
                        </div>
                    </div>
                `;
                document.getElementById('vendabilite-result').style.display = 'block';
            });
    }
    </script>
</body>
</html>