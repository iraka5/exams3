<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { background-color: #333; padding: 10px; margin-bottom: 20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        .btn { display: inline-block; padding: 5px 10px; margin: 2px; text-decoration: none; background-color: #007bff; color: white; border-radius: 3px; font-size: 12px; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; flex: 1; }
        .shortage { background-color: #ffebee; }
        .sufficient { background-color: #e8f5e8; }
        .warning { background-color: #fff3e0; }
        tr.shortage { background-color: #ffcdd2; }
        tr.sufficient { background-color: #c8e6c9; }
        tr.warning { background-color: #ffe0b2; }
    </style>
</head>
<body>

<nav>
    <a href="/regions">Régions</a>
    <a href="/villes">Villes</a>
    <a href="/besoins">Besoins</a>
    <a href="/dons">Dons</a>
    <a href="/tableau-bord">Tableau de Bord</a>
</nav>

<h1>📊 Tableau de Bord - Suivi des Dons BNGRC</h1>

<div class="stats">
    <div class="stat">
        <h2><?= count($villes) ?></h2>
        <p>Villes suivies</p>
    </div>
    <div class="stat">
        <h2><?= array_sum(array_column($villes, 'nb_besoins')) ?></h2>
        <p>Besoins totaux</p>
    </div>
    <div class="stat">
        <h2><?= array_sum(array_column($villes, 'nb_dons')) ?></h2>
        <p>Dons reçus</p>
    </div>
</div>

<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
    <h3>🚨 Légende :</h3>
    <p style="margin: 5px 0;"><span class="shortage" style="padding: 3px 8px; border-radius: 3px;">🔴 Rouge</span> : Besoins supérieurs aux dons</p>
    <p style="margin: 5px 0;"><span class="warning" style="padding: 3px 8px; border-radius: 3px;">🟡 Orange</span> : Dons partiels</p>
    <p style="margin: 5px 0;"><span class="sufficient" style="padding: 3px 8px; border-radius: 3px;">🟢 Vert</span> : Dons suffisants</p>
</div>

<table>
    <thead>
        <tr>
            <th>Ville</th>
            <th>Région</th>
            <th>Nb Besoins</th>
            <th>Nb Dons</th>
            <th>Total Besoins</th>
            <th>Total Dons</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($villes)): ?>
            <tr>
                <td colspan="8" style="text-align: center; color: #999;">
                    Aucune donnée disponible
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($villes as $ville): ?>
                <?php 
                $total_besoins = (float)$ville["total_besoins"];
                $total_dons = (float)$ville["total_dons"];
                
                if ($total_besoins == 0) {
                    $status = "Aucun besoin";
                    $class = "";
                } elseif ($total_dons == 0) {
                    $status = "🔴 Aucun don";
                    $class = "shortage";
                } elseif ($total_dons < $total_besoins) {
                    $percentage = round(($total_dons / $total_besoins) * 100);
                    if ($percentage < 50) {
                        $status = "🔴 Insuffisant ({$percentage}%)";
                        $class = "shortage";
                    } else {
                        $status = "🟡 Partiel ({$percentage}%)";
                        $class = "warning";
                    }
                } else {
                    $status = "🟢 Suffisant";
                    $class = "sufficient";
                }
                ?>
                <tr class="<?= $class ?>">
                    <td><strong><?= htmlspecialchars($ville["nom"]) ?></strong></td>
                    <td><?= htmlspecialchars($ville["region_nom"]) ?></td>
                    <td style="text-align: center;"><?= $ville["nb_besoins"] ?></td>
                    <td style="text-align: center;"><?= $ville["nb_dons"] ?></td>
                    <td style="text-align: right;"><?= number_format($total_besoins, 2) ?></td>
                    <td style="text-align: right;"><?= number_format($total_dons, 2) ?></td>
                    <td><?= $status ?></td>
                    <td>
                        <a href="/villes/<?= $ville["id"] ?>" class="btn">👁️ Voir</a>
                        <a href="/besoins/create?ville_id=<?= $ville["id"] ?>" class="btn">➕ Besoin</a>
                        <a href="/dons/create?ville_id=<?= $ville["id"] ?>" class="btn">🎁 Don</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div style="text-align: center; margin-top: 30px;">
    <p style="color: #666;">
        📊 Ce tableau est mis à jour en temps réel selon les besoins et dons saisis.
    </p>
</div>

</body>
</html>