<form method="GET" action="/besoins">
    <label>Filtrer par ville :</label>
    <select name="id_ville">
        <option value="0">-- Toutes les villes --</option>
        <?php foreach ($villes as $v): ?>
            <option value="<?= $v["id"] ?>" <?= ($id_ville == $v["id"]) ? "selected" : "" ?>>
                <?= htmlspecialchars($v["nom"]) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrer</button>
</form>
