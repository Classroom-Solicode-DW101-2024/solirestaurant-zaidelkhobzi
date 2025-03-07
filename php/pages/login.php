<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        require "../connectDB.php";

        $tel = trim($_POST["telCl"]);

        if(empty($tel)) {
            die("Phone number is required!");
        }
        else {
            $sql = "SELECT * FROM client WHERE telCl = :telCl";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":telCl", $tel);
    
            $stmt->execute();
    
            $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($client) {
                if(!isset($_SESSION["client"])) {
                    $_SESSION["client"] = [];
                }
                $_SESSION["client"]["client_id"] = $client["idClient"];

                session_regenerate_id(true);
                /** Session Regeneration: For better security, you can regenerate session IDs after successful login. This ensures that after a successful login, the session ID is regenerated to prevent session hijacking. */

                header("Location: plats.php");
                exit();
            }
            else {
                echo "Phone number not found!";
            }
        }
    }
?>

<?php include "../../dist/login.html" ?>