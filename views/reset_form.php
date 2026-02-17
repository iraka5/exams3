<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --danger: #dc3545; --warning: #ffc107; --success: #28a745; }
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

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

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
        .card-header.warning {
            background: #fff3cd;
            color: #856404;
            border-left: 5px solid var(--warning);
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
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--brand);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin: 15px 0;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e6e9ef;
        }
        th {
            background: #f8f9fa;
            color: var(--brand);
            font-weight: 600;
        }

        .list-group {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        .list-group-item {
            padding: 8px 0;
            border-bottom: 1px solid #e6e9ef;
            font-size: 14px;
        }
        .list-group-item:last-child {
            border-bottom: none;
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
        .btn-danger { background: var(--danger); color: white; }
        .btn-warning { background: var(--warning); color: #212529; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--muted);
            font-size: 14px;
        }

        .flex-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .flex-item { flex: 1; }

        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .flex-row { flex-direction: column; }
            .flex-item { width: 100%; }
            .btn { width: 100%; margin: 5px 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Administration</h1>
        <p>Réinitialisation des données du système</p>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/tableau-bord">Tableau de bord</a>
        <a href="<?= $base ?>/logout">Déconnexion</a>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header warning">
                <h4>
                    <span style="font-size: 24px;">⚠️</span>
                    Réinitialisation des données
                </h4>
                <p class="mb-0">Cette action va remettre les données de base et supprimer toutes les données ajoutées.</p>
            </div>
            <div class="card-body">
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <span style="font-size: 20px;">✅</span>
                        <div>
                            <strong>Succès !</strong> Les données ont été réinitialisées avec succès.
                            <?php if (isset($_GET['queries'])): ?>
                                <br><small><?= intval($_GET['queries']) ?> requêtes exécutées.</small>
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
                                case 'confirmation':
                                    echo "Veuillez taper exactement 'RESET_DATA' pour confirmer.";
                                    break;
                                case 'execution':
                                    echo "Erreur lors de l'exécution : " . htmlspecialchars($_GET['message'] ?? 'Erreur inconnue');
                                    break;
                                default:
                                    echo "Une erreur est survenue.";
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Statistiques actuelles -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">Régions</div>
                        <div class="stat-value"><?= $stats['regions'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Villes</div>
                        <div class="stat-value"><?= $stats['villes'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Besoins</div>
                        <div class="stat-value"><?= $stats['besoins'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Dons</div>
                        <div class="stat-value"><?= $stats['dons'] ?? 0 ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Échanges</div>
                        <div class="stat-value"><?= $stats['echanges'] ?? 0 ?></div>
                    </div>
                    <?php if (($stats['achats'] ?? 0) > 0): ?>
                        <div class="stat-item">
                            <div class="stat-label">Achats</div>
                            <div class="stat-value"><?= $stats['achats'] ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin: 25px 0;">
                    <div>
                        <h5 style="color: var(--brand); margin-bottom: 15px;">Derniers besoins</h5>
                        <?php if (!empty($besoins_recents)): ?>
                            <ul class="list-group">
                                <?php foreach (array_slice($besoins_recents, 0, 5) as $besoin): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($besoin['nom']) ?></strong>
                                        <span style="color: var(--muted); font-size: 12px; margin-left: 10px;">
                                            (<?= htmlspecialchars($besoin['ville_nom']) ?>)
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p style="color: var(--muted);">Aucun besoin enregistré</p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <h5 style="color: var(--brand); margin-bottom: 15px;">Derniers dons</h5>
                        <?php if (!empty($dons_recents)): ?>
                            <ul class="list-group">
                                <?php foreach (array_slice($dons_recents, 0, 5) as $don): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($don['type_don']) ?></strong>
                                        <span style="color: var(--muted); font-size: 12px;">
                                            par <?= htmlspecialchars($don['nom_donneur']) ?>
                                            (<?= htmlspecialchars($don['ville_nom']) ?>)
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p style="color: var(--muted);">Aucun don enregistré</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Avertissement -->
                <div class="alert alert-warning" style="margin: 25px 0;">
                    <span style="font-size: 24px; margin-right: 15px;">⚠️</span>
                    <div>
                        <h5 style="margin: 0 0 10px 0; color: #856404;">Attention !</h5>
                        <p style="margin: 0 0 10px 0;">Cette opération va :</p>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Supprimer tous les <strong>besoins, dons, échanges et achats</strong> actuels</li>
                            <li>Restaurer les <strong>données de base</strong> (besoins et dons d'exemple)</li>
                            <li>Conserver les <strong>régions et villes</strong> existantes</li>  
                            <li>Conserver les <strong>paramètres de configuration</strong></li>
                        </ul>
                        <p style="margin: 10px 0 0 0;"><strong>Cette action est irréversible !</strong></p>
                    </div>
                </div>
                
                <!-- Formulaire de confirmation -->
                <form method="POST" action="<?= BASE_URL ?>/reset-data" onsubmit="return confirmReset()" id="resetForm">
                    <div class="flex-row">
                        <div class="flex-item">
                            <label for="confirm" class="form-label">
                                Pour confirmer, tapez exactement : <code>RESET_DATA</code>
                            </label>
                            <input 
                                type="text" 
                                id="confirm" 
                                name="confirm" 
                                class="form-control" 
                                placeholder="RESET_DATA"
                                required
                            >
                        </div>
                        <div class="flex-item" style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-danger" id="resetBtn" disabled style="flex: 2;">
                                <span style="font-size: 16px;">🔄</span> Réinitialiser les données
                            </button>
                            <a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-secondary" style="flex: 1;">
                                <span style="font-size: 16px;">←</span> Retour
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function confirmReset() {
        const confirmation = document.getElementById('confirm').value;
        if (confirmation !== 'RESET_DATA') {
            alert('Veuillez taper exactement "RESET_DATA" pour confirmer.');
            return false;
        }
        
        return confirm('Êtes-vous absolument sûr de vouloir réinitialiser toutes les données ? Cette action est irréversible !');
    }

    // Activer le bouton seulement si la confirmation est correcte
    document.getElementById('confirm').addEventListener('input', function() {
        const resetBtn = document.getElementById('resetBtn');
        if (this.value === 'RESET_DATA') {
            resetBtn.disabled = false;
            resetBtn.classList.remove('btn-danger');
            resetBtn.classList.add('btn-warning');
        } else {
            resetBtn.disabled = true;
            resetBtn.classList.remove('btn-warning');
            resetBtn.classList.add('btn-danger');
        }
    });
    </script>
</body>
</html>