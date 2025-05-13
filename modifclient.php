<?php
    include_once("main.php");
    if(!empty($_POST)){
        $query="update client set idclient=:idclient,nom=:nom,ville=:ville,telephone=:telephone where idclient=:id";
        $pdostmt=$pdo->prepare("$query");
        $pdostmt->execute(["idclient"=>$_POST["idclient"],"nom"=>$_POST["nom"],"ville"=>$_POST["ville"],"telephone"=>$_POST["telephone"],"id"=>$_POST["myid"]]);
        header("Location:clients.php");
    }

     if(!empty($_GET["id"])){
        $query="select *from client where idclient=:id";
        $pdostmt=$pdo->prepare($query);
        $pdostmt->execute(["id"=>$_GET["id"]]);
        while($row=$pdostmt->fetch(PDO::FETCH_ASSOC)):
?>
    <form method="post">
        <input type="hidden" name="myid" value="<?php echo $row["idclient"] ?>">
        <fieldset>
            <legend>Ajout d'un Client</legend>
            <label for="idclient">CodeClient</label>
            <input type="text" name="idclient" value="<?php echo $row["idclient"] ?>" required>
            <label for="nom">Nom</label>
            <input type="text" name="nom" value="<?php echo $row["nom"] ?>" required>
            <label for="ville">Ville</label>
            <input type="text" name="ville" value="<?php echo $row["ville"] ?>" required>
            <label for="telephone">Telephone</label>
            <input type="text" name="telephone" value="<?php echo $row["telephone"] ?>" required>
            <button type="submit" name="valide">Modifier</button>
        </fieldset>
    </form>
<?php
    endwhile;
    }
?>