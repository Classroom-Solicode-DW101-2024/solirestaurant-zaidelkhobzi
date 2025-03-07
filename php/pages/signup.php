<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        require "../connectDB.php";

        $name = trim($_POST["nomCl"]) ?? "";
        $prenom = trim($_POST["prenomCl"]) ?? "";
        $tel = trim($_POST["telCl"]) ?? "";

        $errors = [];

        if(empty($name)) $errors[] = "Name is required.";
        if(empty($prenom)) $errors[] = "Prenom is required.";
        if(empty($tel)) $errors[] = "Tel is required.";

        if(!empty($errors)) {
            echo "<pre>";
            print_r($errors);
            echo "</pre>";
        }
        else {
            function getMaxId() {
                global $pdo;
    
                $sql = "SELECT MAX(idClient) AS MaxId FROM client";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
    
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
                return $row["MaxId"] ? $row["MaxId"] + 1 : 1;
            }
    
            $sql = "INSERT INTO client (idClient, nomCl, prenomCl, telCl) VALUES (:idClient, :nomCl, :prenomCl, :telCl)";
        
            $stmt = $pdo->prepare($sql);
    
            $idClient = getMaxId();
    
            $stmt->bindParam(":idClient", $idClient);
            $stmt->bindParam(":nomCl", $name);
            $stmt->bindParam(":prenomCl", $prenom);
            $stmt->bindParam(":telCl", $tel);
    
            if ($stmt->execute()) {
                // echo "Client added successfully!";
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        }
    }
?>

<?php include "../../dist/signup.html" ?>