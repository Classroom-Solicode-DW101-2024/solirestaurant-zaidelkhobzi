<?php
    session_start();

    if(isset($_POST["supprimer"])) {
        $id_plat = $_POST["idPlat"];
        $id_client = $_SESSION["client"]["client_id"];

        unset($_SESSION["panier"][$id_client][$id_plat]);

        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }