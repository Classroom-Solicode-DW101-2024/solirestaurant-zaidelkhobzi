<?php
ob_start();
session_start();
require "../connectDB.php";

$id_client = $_SESSION["client"]["client_id"];

if (!empty($_SESSION["panier"][$id_client])) {
    $panier = $_SESSION["panier"][$id_client];
    $id_plat = array_keys($panier);

    if (!empty($id_plat)) {
        $placeholders = implode(",", array_fill(0, count($id_plat), "?"));
        $sql = "SELECT * FROM plat WHERE idPlat IN($placeholders)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($id_plat);
        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $plats = [];
    }
} else {
    $plats = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="../../dist/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css"> -->
    <style>
        table {
            margin-top: 2rem;
            margin-left: 12rem;
        }

        .img-plat {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        .minus,
        .plus {
            color: black;
            cursor: pointer;
            padding: 0 7px;
            height: 23px;
            line-height: 23px;
        }

        .quantite-counter {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .quantite-counter input {
            width: 50px;
            margin: 0 15px;
            padding: 0 0 0 5px;
            height: auto;
            font-size: 18px
        }

        .supprimer {
            margin-left: 10px;
        }

        .button,
        button,
        input[type=button],
        input[type=reset],
        input[type=submit] {
            display: inline-block;
            height: 38px;
            padding: 0 30px;
            color: #555;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            line-height: 38px;
            letter-spacing: .1rem;
            text-transform: uppercase;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border-radius: 4px;
            border: 1px solid #bbb;
            cursor: pointer;
            box-sizing: border-box;
        }

        .vidervalider {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>

<body>
    <?php include "../../dist/header-plats.html" ?>
    <?php
    if (empty($panier)) { ?>
        <p style="background-color: #d5d51287; border-radius: 10px; font-size: 14px; padding: 15px; margin: 30px 20px 0">
            Votre Panier Est Vide</p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Nom du Plat</th>
                    <th>Quantite</th>
                    <th>Prix Unitaire (MAD)</th>
                    <th>Prix Total (MAD)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($plats as $plat):

                    $prix_total = $plat["prix"] * $panier[$plat["idPlat"]];
                    $total += $prix_total;
                    ?>
                    <tr>
                        <?php
                        if (isset($_POST["plus"])) {
                            $idPlat = $_POST["idPlat"];
                            $quantite = (int) $_POST["quantite"];

                            $_SESSION["panier"][$id_client][$idPlat] = $quantite;

                            header("Location: " . $_SERVER["HTTP_REFERER"]);
                            ob_end_flush(); // Send buffered output (optional)
                            exit();
                        }
                        ?>
                        <form class="plat-panier" method="POST">
                            <input type="hidden" name="idPlat" value="<?= htmlspecialchars($plat["idPlat"]) ?>">
                            <td><?= htmlspecialchars($plat["idPlat"]) ?></td>
                            <td><img class="img-plat" src="<?= htmlspecialchars($plat["image"]) ?>" alt="Plat"></td>
                            <td><?= htmlspecialchars($plat["nomPlat"]) ?></td>
                            <td class="quantite-counter">
                                <button class="minus">-</button>
                                <input class="quantite" type="number" name="quantite"
                                    value="<?= htmlspecialchars($panier[$plat["idPlat"]]) ?>">
                                <button class="plus" name="plus">+</button>
                                <button class="supprimer" formaction="supprimer_panier.php" name="supprimer">Supprimer</button>
                            </td>
                            <td><?= htmlspecialchars($plat["prix"]) ?> MAD</td>
                            <td><?= htmlspecialchars($plat["prix"] * $panier[$plat["idPlat"]]) ?> MAD</td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong><?= htmlspecialchars($total) ?> MAD</strong></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        $id_client = $_SESSION["client"]["client_id"] ?? null;

                        if (isset($_POST["vider"])) {
                            $_SESSION["panier"][$id_client] = [];

                            header("Location: panier.php");
                            exit();
                        }

                        if (isset($_POST["valider"])) {
                            $panier_utilisateur = $_SESSION["panier"][$id_client];
                            // $sql = "INSERT INTO commande_plat(idPlat, idCmd, qte) VALUES";
                            $sql = "INSERT IGNORE INTO commande_plat(idPlat, idCmd, qte) VALUES";
                            $i = 0;

                            foreach ($panier_utilisateur as $panier) {
                                $i++;
                            }

                            var_dump($i);

                            $infos_plat = [];

                            function getMaxId()
                            {
                                global $pdo;

                                $stmt = $pdo->prepare("SELECT MAX(idCmd) AS MaxId FROM commande");
                                $stmt->execute();

                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                return $row["MaxId"] ? $row["MaxId"] + 1 : 1;
                            }

                            // function getMaxId() {
                            //     global $pdo;
                            //     return $pdo->lastInsertId() ? $pdo->lastInserId() + 1 : 1;
                            // }
                    
                            $idCommande = getMaxId();

                            $sqlCommande = $pdo->prepare("INSERT INTO commande(idCmd, idCl) VALUES(:idCmd, :idCl)");

                            $sqlCommande->bindParam(":idCmd", $idCommande);
                            $sqlCommande->bindParam(":idCl", $id_client);

                            $sqlCommande->execute();

                            foreach ($plats as $plat) {
                                $id_plat = $plat["idPlat"];
                                $qty = $panier_utilisateur[$id_plat];

                                $infos_plat = [
                                    "idPlat" => $id_plat
                                ];
                            }

                            foreach ($panier_utilisateur as $id_plat => $qty) {
                                // var_dump($id_plat, $qty);
                                $sqlState = $pdo->prepare("INSERT INTO commande_plat(idPlat, idCmd, qte) VALUES(:idPlat, :idCmd, :quantite)");

                                $sqlState->bindParam(":idPlat", $id_plat);
                                $sqlState->bindParam(":idCmd", $idCommande);
                                $sqlState->bindParam(":quantite", $qty);

                                $commande_plat = $sqlState->execute();
                            }
                            
                            foreach ($panier_utilisateur as $id_plat => $qty) {
                                $sql .= "(:idPlat$i, :idCmd$i, :quantite$i),";
                            }

                            $sql = substr($sql, 0, -1);

                            $sqlState = $pdo->prepare($sql);

                            foreach ($panier_utilisateur as $id_plat => $qty) {
                                $sqlState->bindParam(":idPlat" . $i, $id_plat);
                                $sqlState->bindParam(":idCmd" . $i, $idCommande);
                                $sqlState->bindParam(":quantite" . $i, $qty);
                            }
                        }
                        ?>
                        <form class="vidervalider" method="POST">
                            <input type="submit" name="valider" value="Valider la commande">
                            <input onclick="return confirm('Voulez vous vraiment vider votre panier ?')" type="submit"
                                name="vider" value="Vider le panier">
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    <?php } ?>

    <?php include "../../dist/popup-panier.html" ?>

    <script src="../../src/js/main.js"></script>
</body>

</html>