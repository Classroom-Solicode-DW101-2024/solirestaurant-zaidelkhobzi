<?php
    session_start();
    require "../connectDB.php";

    if(!isset($_SESSION["client"]["client_id"])) {
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM plat";

    $stmt = $pdo->prepare($sql);

    if(isset($_POST["appliquer"])) {
        $categoriePlat = trim($_POST["category_check"]);
        $typeCuisine = trim($_POST["cuisine_check"]);

        if(!empty($_POST["category_check"]) && !empty($_POST["cuisine_check"])) {
            $sql = "SELECT * FROM plat WHERE categoriePlat = :categoriePlat AND TypeCuisine = :TypeCuisine";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":categoriePlat", $categoriePlat);
            $stmt->bindParam(":TypeCuisine", $typeCuisine);
        }
        elseif(!empty($_POST["category_check"])) {
            $sql = "SELECT * FROM plat WHERE categoriePlat = :categoriePlat";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":categoriePlat", $categoriePlat);
        }
        elseif(!empty($_POST["cuisine_check"])) {
            $sql = "SELECT * FROM plat WHERE TypeCuisine = :TypeCuisine";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":TypeCuisine", $typeCuisine);
        }
        else {
            $sql = "SELECT * FROM plat";

            $stmt = $pdo->prepare($sql);
        }

    }

    $stmt->execute();

    $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST["nettoyer"])) {
        $sql = "SELECT * FROM plat";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Grouping plats by TypeCuisine
    $groupedPlats = [];
    foreach ($plats as $plat) {
        $groupedPlats[$plat["TypeCuisine"]][] = $plat;
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php include "../../dist/head.html" ?>

<body>
    <?php include "../../dist/header-plats.html" ?>

    <?php include "../../dist/plats.html" ?>

    <?php include "../../dist/footer.html" ?>
</body>

</html>