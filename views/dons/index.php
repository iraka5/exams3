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
        body { font-family: Arial, sans-serif; background: #f6f8fb; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .btn { display: inline-block; padding: 8px 15px; border-radius: 999px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; background: #13265C; color: white; text-decoration: none; margin-right: 5px; }
        .btn:hover { opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #e6e9ef; font-size: 14px; text-align: left; }
        th { background: #13265C; color: white; }
        tr:nth-child(even) { background: #f9fafc; }
        tr:hover { background: rgba(19,38,92,0.05); }
        .no-data { text-align: center; padding: 20px; color: #6b7280; }
        .badge { padding: 8px 12px; border-radius: 999px; font-size: 12px; color: white; }
        .badge-vendu { background: #6c757d; }
        .btn-vendre { background: #17a2b8; color: white; }
    </style>
    <script>
        function confirmVente() {
            return confirm("Confirmer la vente de ce don ?");
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Dons</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dons)): ?>
                <tr>
                    <td colspan="5" class="no-data">Aucun don disponible</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= htmlspecialchars($don['id']) ?></td>
                        <td><?= htmlspecialchars($don['description']) ?></td>
                        <td><?= number_format(floatval($don['montant']), 2, ',', ' ') ?> Ar</td>
                        <td><?= htmlspecialchars($don['ville_nom']) ?></td>
                        <td>
                            <?php if (!($don['vendu'] ?? false)): ?>
                                <a href="<?= $base ?>/dons/<?= $don['id'] ?>/vendre" class="btn btn-vendre" onclick="return confirmVente();">Vendre</a>
                            <?php else: ?>
                                <span class="badge badge-vendu">VENDU</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
