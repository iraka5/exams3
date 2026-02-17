<?php
$base = '/exams3-main/exams3';

// Récupérer le taux actuel
$taux_actuel = 10; // valeur par défaut
foreach ($parametres as $param) {
    if ($param['cle'] === 'taux_diminution_vente') {
        $taux_actuel = (float)$param['valeur'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --danger: #dc3545; --warning: #ffc107; --success: #28a745; --info: #17a2b8; }
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

        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e6e9ef;
        }
        .card-header.primary {
            background: linear-gradient(135deg, var(--brand), #1a2f7a);
            color: white;
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
            font-size: 14px;
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

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 25px 0;
        }

        h5 {
            color: var(--brand);
            font-size: 18px;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--bg);
        }
        h6 {
            color: var(--muted);
            font-size: 16px;
            margin: 15px 0 10px 0;
        }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--muted);
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(19,38,92,0.1);
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #e6e9ef;
            border-left: none;
            padding: 12px 15px;
            border-radius: 0 8px 8px 0;
            color: var(--muted);
        }
        .form-text {
            font-size: 13px;
            color: var(--muted);
            margin-top: 5px;
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
            margin-right: 10px;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: var(--brand); color: white; }
        .btn-outline-primary { 
            background: transparent; 
            border: 2px solid var(--brand); 
            color: var(--brand);
        }
        .btn-outline-primary:hover { background: var(--brand); color: white; }
        .btn-outline-warning {
            background: transparent;
            border: 2px solid var(--warning);
            color: #856404;
        }
        .btn-outline-warning:hover { background: var(--warning); color: #212529; }
        .btn-secondary { background: #6b7280; color: white; }

        .stats-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            text-align: center;
        }
        .stats-number {
            font-size: 28px;
            font-weight: bold;
            color: var(--brand);
            line-height: 1.2;
        }
        .stats-label {
            font-size: 12px;
            color: var(--muted);
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e6e9ef;
        }
        .stats-row:last-child { border-bottom: none; }

        .table-responsive {
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e6e9ef;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th {
            background: var(--brand);
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e6e9ef;
            font-size: 13px;
        }
        tr:hover { background: #f9fafc; }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }

        .flex-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .text-center { text-align: center; }
        .py-4 { padding: 30px 0; }
        .text-muted { color: var(--muted); }
        .fa-3x { font-size: 3em; }

        @media (max-width: 968px) {
            .grid-2 { grid-template-columns: 1fr; }
            .flex-row { flex-direction: column; align-items: stretch; }
            .btn { width: 100%; margin: 5px 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Administration</h1>
        <p>Configuration du système de vente</p>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/tableau-bord">Tableau de bord</a>
        <a href="<?= $base ?>/config-taux" class="active">Configuration</a>
        <a href="<?= $base ?>/logout">Déconnexion</a>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header primary">
                <h4>
                    <span style="font-size: 24px;">⚙️</span>
                    Configuration du système de vente
                </h4>
                <p class="mb-0">Gérer le pourcentage de diminution appliqué lors de la vente d'articles</p>
            </div>
            <div class="card-body">
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <span style="font-size: 20px;">✅</span>
                        <div>
                            <strong>Succès !</strong> Le taux de diminution a été mis à jour.
                            <?php if (isset($_GET['ancien']) && isset($_GET['nouveau'])): ?>
                                <br><small>Ancien taux : <?= htmlspecialchars($_GET['ancien']) ?>% → Nouveau taux : <?= htmlspecialchars($_GET['nouveau']) ?>%</small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <span style="font-size: 20px;">❌</span>
                        <div>
                            <strong>Erreur !</strong>
                            <?php 
                            switch($_GET['error']) {
                                case 'taux_invalide':
                                    echo "Le taux doit être un nombre entre 0 et 100.";
                                    break;
                                case 'erreur_sauvegarde':
                                    echo "Erreur lors de la sauvegarde des paramètres.";
                                    break;
                                default:
                                    echo "Une erreur est survenue.";
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="grid-2">
                    <!-- Configuration du taux -->
                    <div>
                        <h5>Configuration du taux</h5>
                        
                        <form method="POST" action="<?= BASE_URL ?>/config-taux" id="configForm">
                            <div class="form-group">
                                <label for="taux_diminution" class="form-label">
                                    Taux de diminution lors de la vente (%)
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="number" 
                                        id="taux_diminution" 
                                        name="taux_diminution" 
                                        class="form-control" 
                                        value="<?= $taux_actuel ?>"
                                        min="0" 
                                        max="100" 
                                        step="0.1"
                                        required
                                    >
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">
                                    Pourcentage appliqué automatiquement lors de la conversion d'un don en argent.
                                    <br><strong>Exemple :</strong> Un article de 100 000 Ar avec <?= $taux_actuel ?>% de diminution sera vendu à <?= number_format(100000 * (1 - $taux_actuel/100), 0, ',', ' ') ?> Ar.
                                </div>
                            </div>
                            
                            <div class="flex-row" style="justify-content: flex-start;">
                                <button type="submit" class="btn btn-primary">
                                    <span style="font-size: 16px;">💾</span> Sauvegarder
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="resetToDefault()">
                                    <span style="font-size: 16px;">↩️</span> Valeur par défaut (10%)
                                </button>
                            </div>
                        </form>
                        
                        <!-- Simulateur temps réel -->
                        <div style="margin-top: 30px;">
                            <h6>Simulateur de prix</h6>
                            <div class="input-group" style="margin-bottom: 10px;">
                                <span class="input-group-text">Prix original</span>
                                <input type="number" id="prix_simulation" class="form-control" value="100000" step="1000">
                                <span class="input-group-text">Ar</span>
                            </div>
                            <div class="alert alert-info" id="resultat_simulation">
                                Prix de vente : <strong><?= number_format(100000 * (1 - $taux_actuel/100), 0, ',', ' ') ?> Ar</strong>
                                <br>Perte : <strong><?= number_format(100000 * $taux_actuel/100, 0, ',', ' ') ?> Ar</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiques et historique -->
                    <div>
                        <h5>Statistiques des ventes</h5>
                        
                        <?php if ($stats_vente && $stats_vente['total_ventes'] > 0): ?>
                            <div class="stats-card">
                                <div class="stats-grid">
                                    <div>
                                        <div class="stats-number"><?= $stats_vente['total_ventes'] ?></div>
                                        <div class="stats-label">Articles vendus</div>
                                    </div>
                                    <div>
                                        <div class="stats-number" style="color: var(--success);"><?= number_format($stats_vente['valeur_vente_totale'], 0, ',', ' ') ?> Ar</div>
                                        <div class="stats-label">Valeur récupérée</div>
                                    </div>
                                </div>
                                <div class="stats-grid" style="margin-top: 15px;">
                                    <div>
                                        <div class="stats-number" style="color: var(--warning);"><?= number_format($stats_vente['valeur_originale_totale'] - $stats_vente['valeur_vente_totale'], 0, ',', ' ') ?> Ar</div>
                                        <div class="stats-label">Perte totale</div>
                                    </div>
                                    <div>
                                        <div class="stats-number" style="color: var(--info);"><?= number_format($stats_vente['taux_moyen_realise'], 1) ?>%</div>
                                        <div class="stats-label">Taux moyen appliqué</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($dernieres_ventes)): ?>
                                <h6 style="margin-top: 20px;">Dernières ventes</h6>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Article</th>
                                                <th>Prix orig.</th>
                                                <th>Prix vente</th>
                                                <th>Taux</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dernieres_ventes as $vente): ?>
                                                <tr>
                                                    <td>
                                                        <div><?= htmlspecialchars($vente['type_don']) ?></div>
                                                        <small style="color: var(--muted);"><?= htmlspecialchars($vente['ville_nom']) ?></small>
                                                    </td>
                                                    <td><?= number_format($vente['prix_original'], 0, ',', ' ') ?></td>
                                                    <td><?= number_format($vente['prix_vente'], 0, ',', ' ') ?></td>
                                                    <td>
                                                        <span class="badge badge-<?= $vente['taux_applique'] > 15 ? 'danger' : ($vente['taux_applique'] > 10 ? 'warning' : 'success') ?>">
                                                            <?= number_format($vente['taux_applique'], 1) ?>%
                                                        </span>
                                                    </td>
                                                    <td><small><?= date('d/m H:i', strtotime($vente['date_vente'])) ?></small></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <div style="font-size: 48px; margin-bottom: 15px;">📊</div>
                                <p style="margin-bottom: 5px;">Aucune vente enregistrée pour le moment.</p>
                                <small>Les statistiques apparaîtront après les premières ventes d'articles.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e6e9ef;">
                    <div class="flex-row">
                        <div>
                            <a href="<?= $base ?>/dons" class="btn btn-outline-primary">
                                <span style="font-size: 16px;">🎁</span> Voir les dons
                            </a>
                            <a href="<?= $base ?>/reset-data" class="btn btn-outline-warning">
                                <span style="font-size: 16px;">🔄</span> Réinitialiser données
                            </a>
                        </div>
                        <a href="<?= $base ?>/tableau-bord" class="btn btn-secondary">
                            <span style="font-size: 16px;">←</span> Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function resetToDefault() {
        document.getElementById('taux_diminution').value = 10;
        updateSimulation();
    }

    function updateSimulation() {
        const prix = parseFloat(document.getElementById('prix_simulation').value) || 0;
        const taux = parseFloat(document.getElementById('taux_diminution').value) || 0;
        
        const prixVente = prix * (1 - taux/100);
        const perte = prix * taux/100;
        
        document.getElementById('resultat_simulation').innerHTML = 
            `Prix de vente : <strong>${prixVente.toLocaleString('fr-FR')} Ar</strong><br>` +
            `Perte : <strong>${perte.toLocaleString('fr-FR')} Ar</strong>`;
    }

    // Mettre à jour la simulation en temps réel
    document.getElementById('prix_simulation').addEventListener('input', updateSimulation);
    document.getElementById('taux_diminution').addEventListener('input', updateSimulation);

    // Validation du formulaire
    document.getElementById('configForm').addEventListener('submit', function(e) {
        const taux = parseFloat(document.getElementById('taux_diminution').value);
        if (isNaN(taux) || taux < 0 || taux > 100) {
            e.preventDefault();
            alert('Le taux doit être un nombre entre 0 et 100.');
            return false;
        }
        
        if (taux > 50) {
            if (!confirm(`Attention ! Un taux de ${taux}% est très élevé. Continuer ?`)) {
                e.preventDefault();
                return false;
            }
        }
    });
    </script>
</body>
</html>