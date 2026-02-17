<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Tableau de Bord</title>
    <style>
        /* ===== STYLE INSPIRÉ DE LA PHOTO ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: #f5f7fb;
            padding: 30px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header avec logo */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #1a2639;
        }

        .logo span {
            color: #3a7bd5;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-name {
            color: #7f8c8d;
            font-weight: 500;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3a7bd5, #2c3e50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .btn-logout {
            width: 35px;
            height: 35px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s;
            border: 1px solid #ecf0f1;
        }

        .btn-logout:hover {
            background: #fee;
            color: #e74c3c;
            border-color: #fee;
        }

        /* Navigation */
        .nav {
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .nav a {
            color: #95a5a6;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav a:hover,
        .nav a.active {
            color: #3a7bd5;
        }

        /* Titre de la page */
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a2639;
            margin-bottom: 25px;
        }

        /* Cartes de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a2639;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Titre de section */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a2639;
        }

        .section-link {
            color: #3a7bd5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .section-link:hover {
            text-decoration: underline;
        }

        /* Tableau */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px 10px;
            color: #7f8c8d;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #ecf0f1;
        }

        td {
            padding: 15px 10px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8f9fa;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Boutons */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .btn:hover {
            background: #e9ecef;
        }

        .btn-primary {
            background: #3a7bd5;
            color: white;
        }

        .btn-primary:hover {
            background: #2c5aa6;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .btn-large {
            padding: 15px 30px;
            font-size: 16px;
        }

        /* Liens rapides */
        .quick-actions {
            text-align: center;
            margin: 40px 0;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #95a5a6;
            font-size: 13px;
            border-top: 1px solid #ecf0f1;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .nav {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            
            .nav a {
                width: 100%;
                text-align: center;
            }
            
            .section-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header avec logo -->
        <div class="header">
            <div class="logo">
                BNG<span>RC</span>
            </div>
            <div class="user-menu">
                <span class="user-name">Admin BNGRC</span>
                <div class="avatar">AD</div>
                <a href="<?= $base ?>/logout" class="btn-logout" title="Déconnexion">↪</a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="nav">
            <a href="<?= $base ?>/" class="active">Accueil</a>
            <a href="<?= $base ?>/regions">Régions</a>
            <a href="<?= $base ?>/villes">Villes</a>
            <a href="<?= $base ?>/besoins">Besoins</a>
            <a href="<?= $base ?>/dons">Dons</a>
            <a href="<?= $base ?>/tableau-bord">Tableau de bord</a>
        </div>

        <!-- Titre de la page -->
        <h1 class="page-title">Tableau de bord</h1>

        <!-- Statistiques générales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🗺️</div>
                <div class="stat-value"><?= $stats['regions'] ?? 0 ?></div>
                <div class="stat-label">Régions</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏘️</div>
                <div class="stat-value"><?= $stats['villes'] ?? 0 ?></div>
                <div class="stat-label">Villes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-value"><?= $stats['besoins'] ?? 0 ?></div>
                <div class="stat-label">Besoins</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎁</div>
                <div class="stat-value"><?= $stats['dons'] ?? 0 ?></div>
                <div class="stat-label">Dons</div>
            </div>
        </div>

        <!-- Dernières régions ajoutées -->
        <div class="section-header">
            <h2 class="section-title">🗺️ Dernières régions</h2>
            <a href="<?= $base ?>/regions" class="section-link">Voir toutes les régions →</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dernieres_regions)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #95a5a6;">
                                Aucune région enregistrée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dernieres_regions as $r): ?>
                        <tr>
                            <td><strong>#<?= $r['id'] ?></strong></td>
                            <td><?= htmlspecialchars($r['nom']) ?></td>
                            <td><?= isset($r['created_at']) ? date('d/m/Y', strtotime($r['created_at'])) : 'N/A' ?></td>
                            <td>
                                <a href="<?= $base ?>/regions/<?= $r['id'] ?>" class="btn btn-primary">Voir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Dernières villes ajoutées -->
        <div class="section-header">
            <h2 class="section-title">🏘️ Dernières villes</h2>
            <a href="<?= $base ?>/villes" class="section-link">Voir toutes les villes →</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Région</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dernieres_villes)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #95a5a6;">
                                Aucune ville enregistrée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dernieres_villes as $v): ?>
                        <tr>
                            <td><strong>#<?= $v['id'] ?></strong></td>
                            <td><?= htmlspecialchars($v['nom']) ?></td>
                            <td><?= htmlspecialchars($v['region_nom'] ?? 'N/A') ?></td>
                            <td><?= isset($v['created_at']) ? date('d/m/Y', strtotime($v['created_at'])) : 'N/A' ?></td>
                            <td>
                                <a href="<?= $base ?>/villes/<?= $v['id'] ?>" class="btn btn-primary">Voir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Derniers besoins -->
        <div class="section-header">
            <h2 class="section-title">📦 Derniers besoins</h2>
            <a href="<?= $base ?>/besoins" class="section-link">Voir tous les besoins →</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Besoin</th>
                        <th>Ville</th>
                        <th>Quantité</th>
                        <th>Urgence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($derniers_besoins)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #95a5a6;">
                                Aucun besoin enregistré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($derniers_besoins as $b): ?>
                        <tr>
                            <td><strong>#<?= $b['id'] ?></strong></td>
                            <td><?= htmlspecialchars($b['nom']) ?></td>
                            <td><?= htmlspecialchars($b['ville_nom'] ?? 'N/A') ?></td>
                            <td><?= $b['quantite'] ?? 0 ?></td>
                            <td>
                                <?php if (($b['quantite'] ?? 0) > 100): ?>
                                    <span class="badge badge-warning">Urgent</span>
                                <?php elseif (($b['quantite'] ?? 0) > 50): ?>
                                    <span class="badge badge-info">Modéré</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Faible</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= $base ?>/besoins/<?= $b['id'] ?>" class="btn btn-primary">Voir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Liens rapides -->
        <div class="quick-actions">
            <a href="<?= $base ?>/create" class="btn btn-success btn-large">➕ Créer (Région, Ville, Besoin)</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            Powered by BNGRC · Bureau National de Gestion des Risques et Catastrophes · <?= date('Y') ?>
        </div>
    </div>
</body>
</html>