<?php
// Ce fichier reçoit $besoins et $villes du index.php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .header { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        select, button { padding: 10px; border-radius: 8px; border: 1px solid #ddd; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background: #34495e; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #ecf0f1; }
        tr:hover { background: #f5f5f5; }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin: 5px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #27ae60, #2ecc71);
            transition: width 0.3s;
        }
        .prix-info {
            color: #27ae60;
            font-weight: bold;
        }
        .montant-total {
            color: #e74c3c;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
        .summary {
            background: #34495e;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
        }
        .summary span {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
            margin-left: 15px;
        }
        .btn-achat {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-achat:hover {
            background: #229954;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Liste des Besoins</h1>
    </div>
    
    <div class="container">
        <a href="<?= $base ?>/user/dashboard" class="back-link">← Retour au Dashboard</a>
        
        <a href="<?= $base ?>/achats/create" class="btn-achat">➕ Nouvel Achat</a>
        
        <div class="filter-section">
            <form method="GET" action="">
                <label for="id_ville">Filtrer par ville :</label>
                <select name="id_ville" id="id_ville" onchange="this.form.submit()">
                    <option value="0">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= $ville['id'] ?>" <?= (isset($_GET['id_ville']) && $_GET['id_ville'] == $ville['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ville['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire (Ar)</th>
                    <th>Montant Total (Ar)</th>
                    <th>Progression</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalGeneral = 0;
                if (empty($besoins)): 
                ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">
                            Aucun besoin trouvé pour cette ville
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($besoins as $besoin): 
                        // Récupérer les dons pour ce besoin (à adapter selon votre structure de base)
                        $db = getDB();
                        $stmt = $db->prepare("SELECT SUM(nombre_don) as total_dons FROM dons WHERE id_ville = ?");
                        $stmt->execute([$besoin['id_ville']]);
                        $donsData = $stmt->fetch(PDO::FETCH_ASSOC);
                        $dons_recus = $donsData['total_dons'] ?? 0;
                        
                        // Calculs
                        $prix_unitaire = $besoin['prix_unitaire'] ?? rand(1000, 50000); // À remplacer par votre vraie colonne
                        $quantite = $besoin['nombre'];
                        $montant_total = $prix_unitaire * $quantite;
                        $progression = ($dons_recus / $quantite) * 100;
                        if ($progression > 100) $progression = 100;
                        
                        $totalGeneral += $montant_total;
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($besoin['ville_nom']) ?></strong></td>
                        <td><?= htmlspecialchars($besoin['description'] ?? 'Non spécifié') ?></td>
                        <td><?= number_format($quantite, 0, ',', ' ') ?></td>
                        <td class="prix-info"><?= number_format($prix_unitaire, 0, ',', ' ') ?> Ar</td>
                        <td class="montant-total"><?= number_format($montant_total, 0, ',', ' ') ?> Ar</td>
                        <td style="min-width: 200px;">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $progression ?>%"></div>
                            </div>
                            <small><?= number_format($dons_recus, 0, ',', ' ') ?> / <?= number_format($quantite, 0, ',', ' ') ?> unités (<?= number_format($progression, 1) ?>%)</small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($besoins)): ?>
        <div class="summary">
            Montant total des besoins :
            <span><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</span>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>