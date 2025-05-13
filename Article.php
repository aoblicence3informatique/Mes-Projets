<?php
    include_once("main.php");
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Articles</h1>
    </header>
    <div class="container">
        <h2>Liste des Articles</h2>
        <button>Ajouter un Article</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Ordinateur</td>
                    <td>1000€</td>
                    <td>50</td>
                    <td>
                        <button>Modifier</button>
                        <button>Supprimer</button>
                    </td>
                </tr>
                <!-- Autres articles -->
            </tbody>
        </table>
    </div>
</body>
</html>
