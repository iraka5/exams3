<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des ventes - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; --success: #28a745; --info: #17a2b8; }
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: var(--brand);
            margin: 10px 0;
        }
        .stat-label {
            color: var(--muted);
            font-size: 14px;
        }
        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-top: 20px;
        }
        th {
            background: var(--brand);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e6e9ef;
        }
        tr:nth-child(even) { background: #f9fafc; }
        tr:hover { background: rgba(19,38,92,0.05); }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-warning { background: #fff3cd; color: #856404; }

        .no-data {
            text-align: center;
            color: var(--muted);
            padding: 40px;
        }

        h2 {
            color: var(--brand);
            margin: 30px 0 15px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            background: var(--brand);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.9; }

        @media (max-width: 768px) {
            table { font-size: 13px; }
            td, th { padding: 8px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Historique des ventes</h1>
        <p>Suivi des conversions de dons en argent</p>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/ventes" class="active">Ventes</a>
        <a href="<?= $base ?>/config-taux">Configuration</a>
        <a href="<?= $base ?>/logout">Déconnexion</a>
    </nav>

    <div class="container">

        <?php if (isset($_GET['success']) && $_GET['success'] == 'vendu'): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                ✅ Don vendu avec succès !
            </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value"><?= number_format($stats['total_ventes'] ?? 0, 0, ',', ' ') ?></div>
                <div class="stat-label">Ventes réalisées</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💵</div>
                <div class="stat-value"><?= number_format($stats['valeur_vente_totale'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-label">Montant total récupéré</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value"><?= $stats['taux_moyen_realise'] ?? 0 ?>%</div>
                <div class="stat-label">Taux moyen appliqué</div>
            </div>
        </div>

        <h2>Détail des ventes</h2>

        <?php if (empty($ventes)): ?>
            <div class="no-data">
                <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                <h3>Aucune vente enregistrée</h3>
                <p>Les ventes apparaîtront ici après avoir converti des dons en argent.</p>
                <a href="<?= $base ?>/dons" class="btn" style="margin-top: 10px;">Voir les dons disponibles</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Donneur</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Ville</th>
                        <th>Prix original</th>
                        <th>Prix vente</th>
                        <th>Taux</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($vente['date_vente'])) ?></td>
                        <td><strong><?= htmlspecialchars($vente['nom_donneur']) ?></strong></td>
                        <td>
                            <?php 
                            $type_icons = [
                                'nature' => '🌾',
                                'materiaux' => '🧱',
                                'argent' => '💰'
                            ];
                            echo ($type_icons[$vente['type_don']] ?? '📦') . ' ' . ucfirst($vente['type_don']);
                            ?>
                        </td>
                        <td><?= number_format($vente['quantite'], 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($vente['ville_nom']) ?></td>
                        <td><span style="text-decoration: line-through; color: #999;"><?= number_format($vente['prix_original'], 0, ',', ' ') ?> Ar</span></td>
                        <td><strong style="color: var(--success);"><?= number_format($vente['prix_vente'], 0, ',', ' ') ?> Ar</strong></td>
                        <td>
                            <span class="badge <?= $vente['taux_applique'] > 15 ? 'badge-warning' : 'badge-info' ?>">
                                <?= number_format($vente['taux_applique'], 1) ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= $base ?>/dons" class="btn" style="padding: 12px 24px;">← Retour aux dons</a>
        </div>
    </div>
</body>
</html>