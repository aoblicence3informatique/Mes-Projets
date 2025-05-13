<?php
  include_once("main.php");
  if(!empty($_GET["id"]))
  {
    $query="delete from client where idclient=:id";
    $pdostmt=$pdo->prepare($query);
    $pdostmt-> execute(["id"=>$_GET["id"]]);
    $pdostmt-> closeCursor();
    header("Location:clients.php");
  }
?>