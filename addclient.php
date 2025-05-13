<?php
    include_once("main.php");
    if(!empty($_POST["idclient"])&&!empty($_POST["nom"])&&!empty($_POST["ville"])&&!empty($_POST["telephone"]))
    {
        $query="insert into client(idclient,nom,ville,telephone) values(:idclient,:nom,:ville,:telephone)";
        $pdostmt=$pdo->prepare($query);
        $pdostmt->execute(["idclient"=>$_POST["idclient"],"nom"=>$_POST["nom"],"ville"=>$_POST["ville"],"telephone"=>$_POST["telephone"]]);
        $pdostmt->closeCursor();
        header("Location:clients.php");
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Ajout client</title>
</head>
<body>
    <form method="post">
        <fieldset>
            <legend>Ajout d'un Client</legend>
            <label for="idclient">CodeClient</label>
            <input type="text" name="idclient" required>
            <label for="nom">Nom</label>
            <input type="text" name="nom" required>
            <label for="ville">Ville</label>
            <input type="text" name="ville" required>
            <label for="telephone">Telephone</label>
            <input type="text" name="telephone" required>
            <button type="submit" name="valide">Ajouter</button>
        </fieldset>
    </form>
</body>
</html>

