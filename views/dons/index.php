<?php
$base = '/exams3-main/exams3';
if (!isset($dons)) $dons = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dons - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
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
        
        h2 {
            color: var(--brand);
            margin: 0 0 20px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            background: var(--brand);
            color: white;
            text-decoration: none;
            margin-right: 5px;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-success { background: #28a745; }
        .btn-vendre { background: #17a2b8; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        th {
            background: var(--brand);
            color: white;
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e6e9ef;
            font-size: 14px;
        }
        tr:nth-child(even) { background: #f9fafc; }
        tr:hover { background: rgba(19,38,92,0.05); }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-vendu {
            background: #6c757d;
            color: white;
        }
        
        .action-buttons {
            margin: 20px 0;
        }
    </style>
    <script>
        function confirmVente() {
            return confirm("Confirmer la vente de ce don ?");
        }
    </script>
</head>
<body>

<div class="header">
    <h1>BNGRC - Gestion des Dons</h1>
    <p>Liste des dons reçus</p>
</div>

<nav>
    <a href="<?= $base ?>/">🏠 Accueil</a>
    <a href="<?= $base ?>/regions">🗺️ Régions</a>
    <a href="<?= $base ?>/villes">🏘️ Villes</a>
    <a href="<?= $base ?>/besoins">📋 Besoins</a>
    <a href="<?= $base ?>/dons" class="active">🎁 Dons</a>
    <a href="<?= $base ?>/ventes">💰 Ventes</a>
    <a href="<?= $base ?>/achats">📝 Achats</a>
    <a href="<?= $base ?>/config-taux">⚙️ Configuration</a>
    <a href="<?= $base ?>/logout">🚪 Déconnexion</a>
</nav>

<div class="container">
    
    <div class="action-buttons">
        <a href="<?= $base ?>/dons/create" class="btn btn-success">➕ Nouveau don</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Donneur</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Ville</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dons)): ?>
                <tr>
                    <td colspan="7" class="no-data">Aucun don disponible</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= htmlspecialchars($don['id'] ?? '') ?></td>
                        <td><strong><?= htmlspecialchars($don['nom_donneur'] ?? '') ?></strong></td>
                        <td><?= htmlspecialchars($don['type_don'] ?? '') ?></td>
                        <td><?= number_format($don['nombre_don'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($don['ville_nom'] ?? '') ?></td>
                        <td>
                            <?php if (isset($don['statut']) && $don['statut'] === 'vendu'): ?>
                                <span class="badge badge-vendu">VENDU</span>
                            <?php else: ?>
                                <span class="badge" style="background: #28a745; color: white;">DISPONIBLE</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!isset($don['statut']) || $don['statut'] !== 'vendu'): ?>
                                <a href="<?= $base ?>/dons/vendre?id=<?= $don['id'] ?>" class="btn btn-vendre" onclick="return confirmVente();">💰 Vendre</a>
                            <?php endif; ?>
                            <a href="<?= $base ?>/dons/<?= $don['id'] ?>/delete" class="btn" style="background: #dc3545;" onclick="return confirm('Supprimer ce don ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>