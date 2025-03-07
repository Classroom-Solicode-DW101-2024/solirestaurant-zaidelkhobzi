<?php $totalCanceled = ""; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - SoliRestaurant</title>
    <link rel="stylesheet" href="../../dist/css/main.css">
</head>

<body class="gestion-restaurant">
    <h1>Gestion des Commandes - SoliRestaurant</h1>

    <!-- Section pour consulter les commandes du jour -->
    <h2>Commandes du Jour</h2>
    <table id="ordersTable">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Client</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connexion à la base de données avec PDO
            try {
                require "../connectDB.php";

                // Récupérer les commandes du jour
                $sql = "SELECT cmd.idCmd, cmd.dateCmd, cmd.Statut, cl.nomCl 
                    FROM commande cmd
                    JOIN client cl ON cmd.idCl = cl.idClient 
                    WHERE DATE(cmd.dateCmd) = CURDATE()";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        /**
                         * If there is still data to fetch, $row will contain an array with column values.
                         * If there are no more rows left, $fetch() returns false, and the loop stops.
                        */
                        echo "
                        <form method='post'>
                        <tr>
                            <td>{$row['idCmd']}</td>
                            <td>{$row['dateCmd']}</td>
                            <td>{$row['Statut']}</td>
                            <td>{$row['nomCl']}</td>
                            <td>
                                <input type='hidden' name='idCmd' value='{$row['idCmd']}'>
                                <select name='select'>
                                    <option value='en attente'>En attente</option>
                                    <option value='en cours'>En cours</option>
                                    <option value='expédiée'>Expédiée</option>
                                    <option value='livrée'>Livrée</option>
                                    <option value='annulée'>Annulée</option>
                                </select>
                                <button name='update'>Update</button>
                            </td>
                        </tr>
                        </form>";
                    }
                    if (isset($_POST["update"])) {
                        $select = $_POST["select"];
                        $idCmd = $_POST["idCmd"]; // Get the idCmd from the form input
            
                        // You can now use the $idCmd and $select values to update the database
                        $sql = "UPDATE commande SET Statut = :Statut WHERE idCmd = :idCmd";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':Statut', $select);
                        $stmt->bindParam(':idCmd', $idCmd);
                        $stmt->execute();

                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM commande WHERE Statut = 'annulée'");
                        $stmt->execute();
                        $totalCanceled = $stmt->fetchColumn();
                    }
                } else {
                    echo "<tr><td colspan='5'>Aucune commande aujourd'hui.</td></tr>";
                }
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
            ?>

            <?php
                
            ?>
        </tbody>
    </table>

    <!-- Section pour les statistiques de gestion -->
    <h2>Statistiques de Gestion</h2>
    <div class="statistics">
        <table border="1">
            <tr>
                <th>Statistique</th>
                <th>Valeur</th>
            </tr>
            <?php
            try {
                $sql = "SELECT COUNT(*) as total_orders FROM commande WHERE DATE(dateCmd) = CURDATE()";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<tr><td>Nombre de commandes effectuées aujourd'hui</td><td><strong>{$row['total_orders']}</strong></td></tr>";

                $sql = "SELECT COUNT(DISTINCT idCl) as total_clients FROM commande";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<tr><td>Nombre de clients total</td><td><strong>{$row['total_clients']}</strong></td></tr>";

                $sql = "SELECT COUNT(*) as cancelled_orders FROM commande WHERE Statut = 'annulée'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<tr><td>Nombre de commandes annulées</td><td><strong>{$row['cancelled_orders']}</strong></td></tr>";

                echo "<tr><td colspan='2'><strong>Plats commandés aujourd'hui</strong></td></tr>";
                echo "<tr><td colspan='2'>
                        <table border='1' width='100%'>
                            <tr>
                                <th style='background-color: #3be0ce'>Nom du Plat</th>
                                <th style='background-color: #3be0ce'>Quantité</th>
                            </tr>";
                $sql = "SELECT p.nomPlat, SUM(cp.qte) as total_qte 
                        FROM commande_plat cp 
                        JOIN plat p ON cp.idPlat = p.idPlat 
                        JOIN commande c ON cp.idCmd = c.idCmd 
                        WHERE DATE(c.dateCmd) = CURDATE() 
                        GROUP BY p.nomPlat";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>{$row['nomPlat']}</td><td>{$row['total_qte']} unités</td></tr>";
                }
                echo "</table></td></tr>";
            } catch (PDOException $e) {
                die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
            }
            ?>
        </table>
    </div>


    <script>
    Fonction pour mettre à jour le statut d 'une commande

    function updateStatus(orderId, status) {
        fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idCmd: orderId,
                    Statut: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Statut mis à jour avec succès !");
                    location.reload(); // Recharger la page pour afficher les changements
                } else {
                    alert("Erreur lors de la mise à jour du statut.");
                }
            });
    }
    </script>
</body>

</html>