<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dons</title>
</head>
<body>

<h2>Liste des dons</h2>

<a href="/dons/create">➕ Ajouter un don</a>

<table border="1" cellpadding="10" cellspacing="0" style="margin-top:15px;">
    <tr>
        <th>ID</th>
        <th>Donneur</th>
        <th>Type don</th>
        <th>Quantité</th>
        <th>Ville</th>
    </tr>

    <?php foreach ($dons as $d): ?>
        <tr>
            <td><?= $d["id"] ?></td>
            <td><?= htmlspecialchars($d["nom_donneur"]) ?></td>
            <td><?= htmlspecialchars($d["type_don"]) ?></td>
            <td><?= $d["nombre_don"] ?></td>
            <td><?= htmlspecialchars($d["ville_nom"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
