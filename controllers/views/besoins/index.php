<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Besoins</title>
</head>
<body>

<h2>Liste des besoins</h2>

<form method="GET" action="/besoins">
    <label>Filtrer par ville :</label>
    <select name="ville_id">
        <option value="0">-- Toutes les villes --</option>
        <?php foreach ($villes as $v): ?>
            <option value="<?= $v["id"] ?>" <?= ($ville_id == $v["id"]) ? "selected" : "" ?>>
                <?= htmlspecialchars($v["nom"]) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrer</button>
</form>

<br>

<a href="/besoins/create">➕ Ajouter un besoin</a>

<table border="1" cellpadding="10" cellspacing="0" style="margin-top:15px;">
    <tr>
        <th>ID</th>
        <th>Besoin</th>
        <th>Quantité</th>
        <th>Ville</th>
    </tr>

    <?php foreach ($besoins as $b): ?>
        <tr>
            <td><?= $b["id"] ?></td>
            <td><?= htmlspecialchars($b["nom"]) ?></td>
            <td><?= $b["nombre"] ?></td>
            <td><?= htmlspecialchars($b["ville_nom"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
