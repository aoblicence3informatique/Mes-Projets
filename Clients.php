<?php
    include_once("main.php");
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Clients</h1>
    </header>
    <div class="container">
        <h2>Liste des Clients</h2>
        <?php
            $query="select *from client";
            $pdostmt=$pdo->prepare($query);
            $pdostmt->execute();
           // var_dump($pdostmt->fetchAll(PDO::FETCH_ASSOC));
        ?>
        <a href="addclient.php">
                <button style="float: right; margin-bottom: 20px ">
                    Ajouter un Client
                </button>
            </a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($ligne=$pdostmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $ligne["idclient"]; ?></td>
                    <td><?php echo $ligne["nom"]; ?></td>
                    <td><?php echo $ligne["ville"]; ?></td>
                    <td><?php echo $ligne["telephone"]; ?></td>
                    <td>
                        <a href="modifclient.php?id=<?php echo $ligne["idclient"]; ?>"><button>Modifier</button></a>
                        <a href="delete.php?id=<?php echo $ligne["idclient"]; ?>"><button>Supprimer</button></a>          
                    </td> 
                </tr>
                    <?php endwhile ?>
                <!-- Autres clients -->
            </tbody>
        </table>
    </div>
</body>
</html>
