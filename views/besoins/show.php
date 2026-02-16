<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Besoin - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        .nav { margin-bottom: 30px; }
        .nav a { color: #3498db; text-decoration: none; margin-right: 15px; }
        .nav a:hover { text-decoration: underline; }
        .detail-group { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        .detail-label { font-weight: 600; color: #2c3e50; margin-bottom: 5px; }
        .detail-value { color: #34495e; font-size: 16px; }
        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; text-align: center; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-back { background: #95a5a6; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="/exams3-main/exams3/">← Accueil</a>
            <a href="/exams3-main/exams3/besoins">Liste des besoins</a>
        </div>

        <h1>Détails du Besoin #<?= $besoin['id'] ?></h1>

        <div class="detail-group">
            <div class="detail-label">Nom du besoin</div>
            <div class="detail-value"><?= htmlspecialchars($besoin['nom']) ?></div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Quantité nécessaire</div>
            <div class="detail-value"><?= number_format($besoin['nombre'], 0, ',', ' ') ?> unités</div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Ville concernée</div>
            <div class="detail-value"><?= htmlspecialchars($besoin['ville_nom']) ?></div>
        </div>

        <div class="btn-group">
            <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/edit" class="btn btn-edit">Modifier</a>
            <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/delete" 
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')" 
               class="btn btn-delete">Supprimer</a>
            <a href="/exams3-main/exams3/besoins" class="btn btn-back">Retour à la liste</a>
        </div>
    </div>
</body>
</html>