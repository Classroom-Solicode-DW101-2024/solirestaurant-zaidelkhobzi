<?php
    session_start();
    require "../connectDB.php";

    $id_client = $_SESSION["client"]["client_id"];

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_plat = $_POST["idPlat"];
        $quantite = $_POST["quantite"];

        if(!isset($_SESSION["panier"])) {
            $_SESSION["panier"] = [];
            /** The reason for initializing $_SESSION["panier"] = []; before setting $_SESSION["panier"]["client_id"] = $id_plat; is to avoid errors when $_SESSION["panier"] is not already set. */
            $_SESSION["panier"][$id_client] = [];
        };

        $_SESSION["panier"][$id_client][$id_plat] = $quantite + 1;

        echo "<pre>";
        print_r($_SESSION["panier"]);
        echo "</pre>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }
        h1, h2, h3, h4, h5, h6, p {
            margin: 0;
            padding: 0
        }
        .plats {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .plat {
            width: calc(100% / 5);
        }
        .plat img {
            width: 150px;
            height: 150px;
        }
        .ajt {
            margin-top: 5px;
        }
        .svg-panier {
            display: flex;
        }
    </style>
</head>
<body>
    <!-- <form method="POST">
        <button type="button">-</button>
        <input type="number" name="quantite" value="0">
        <button type="button">+</button>
        <input type="text" name="valider" value="Valider">
        <input type="text" name="vider" value="Vider">
        <button>ss</button>
    </form> -->
</body>
</html>