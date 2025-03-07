<form action="ajouter_panier.php" method="POST">
    <input type="hidden" name="idPlat" value="<?= htmlspecialchars($plat["idPlat"]) ?>">
    <?php
        $id_client = $_SESSION["client"]["client_id"];
        $id_plat = $plat["idPlat"];
        $quantite = $_SESSION["panier"][$id_client][$id_plat] ?? 0;
    ?>
    <input type="hidden" name="quantite" value="<?= $quantite ?>">

    <?php
        if(isset($_SESSION["panier"][$id_client][$id_plat])):
    ?>
    <button type="button" class="ajt btn-clicked" disabled>Ajouter au panier</button>
    <?php else: ?>
        <button class="ajt">Ajouter au panier</button>
    <?php endif ?>
</form>