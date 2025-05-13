<?php
    include_once("main.php");
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lignecommande</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Lignes de Commande</h1>
    </header>
    <div class="container">
        <h2>Liste des Lignes de Commande</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>ID Article</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Ordinateur</td>
                    <td>2</td>
                    <td>2000€</td>
                </tr>
                <!-- Autres lignes de commande -->
            </tbody>
        </table>
    </div>
</body>
</html>
