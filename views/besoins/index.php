<?php
$base = '/exams3-main/exams3';

if (!isset($villes)) $villes = [];
if (!isset($id_ville)) $id_ville = 0;
if (!isset($ville_selected)) $ville_selected = null;
if (!isset($besoins)) $besoins = [];
$total_montant = 0;

// Calcul du montant total sécurisé
foreach ($besoins as $b) {
    $total_montant += isset($b['montant']) ? floatval($b['montant']) : 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Besoins - BNGRC</title>
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
        .filter-form { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        select { padding: 8px 12px; border-radius: 12px; border: 1px solid #e6e9ef; font-size: 14px; }
        .no-data { text-align: center; padding: 20px; color: #6b7280; }
        .total { margin-top: 15px; font-weight: bold; font-size: 16px; }
        .ville-info { background: #e8f5e8; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Besoins</h1>

    <!-- Filtre par ville -->
    <div class="filter-form">
        <form method="GET" action="<?= $base ?>/besoins">
            <label>Filtrer par ville :</label>
            <select name="id_ville">
                <option value="0">-- Toutes les villes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v['id'] ?? 0) ?>" <?= ($id_ville == ($v['id'] ?? 0)) ? "selected" : "" ?>>
                        <?= htmlspecialchars($v['nom'] ?? '-') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Filtrer</button>
        </form>

        <?php if($id_ville > 0): ?>
            <a href="<?= $base ?>/besoins" class="btn" style="background: #6c757d;">🗑️ Réinitialiser</a>
        <?php endif; ?>
    </div>

    <!-- Affichage ville filtrée -->
    <?php if ($ville_selected): ?>
        <div class="ville-info">
            Affichage des besoins pour la ville : <strong><?= htmlspecialchars($ville_selected['nom'] ?? '-') ?></strong>
        </div>
    <?php endif; ?>

    <!-- Tableau des besoins -->
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
            <?php if (empty($besoins)): ?>
                <tr>
                    <td colspan="5" class="no-data">Aucun besoin trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($besoins as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($b['description'] ?? '-') ?></td>
                        <td><?= number_format(floatval($b['montant'] ?? 0), 2, ',', ' ') ?> Ar</td>
                        <td><?= htmlspecialchars($b['ville_nom'] ?? '-') ?></td>
                        <td>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>" class="btn">Voir</a>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>/edit" class="btn" style="background: #ffc107; color: black;">Modifier</a>
                            <a href="<?= $base ?>/besoins/<?= htmlspecialchars($b['id'] ?? 0) ?>/delete" class="btn" style="background: #dc3545;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Montant total -->
    <div class="total">
        Montant total : <?= number_format($total_montant, 2, ',', ' ') ?> Ar
    </div>

</div>
</body>
</html>
