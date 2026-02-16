<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter besoin</title>
</head>
<body>

<h2>Ajouter un besoin</h2>

<?php if (isset($_GET["error"])): ?>
    <p style="color:red;">❌ Remplis bien tous les champs !</p>
<?php endif; ?>

<form method="POST" action="/besoins/store">
    <label>Nom du besoin :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Quantité :</label><br>
    <input type="number" name="nombre" min="1" required><br><br>

    <label>Ville :</label><br>
    <select name="ville_id" required>
        <option value="">-- Choisir une ville --</option>
        <?php foreach ($villes as $v): ?>
            <option value="<?= $v["id"] ?>">
                <?= htmlspecialchars($v["nom"]) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <button type="submit">Enregistrer</button>
</form>

<br>
<a href="/besoins">⬅ Retour</a>

</body>
</html>
