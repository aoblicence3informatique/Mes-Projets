<?php
    include_once("main.php");
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Commandes</h1>
    </header>
    <div class="container">
        <h2>Liste des Commandes</h2>
        <button>Ajouter une Commande</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Marie Dupont</td>
                    <td>2025-04-25</td>
                    <td>500€</td>
                    <td>
                        <button>Modifier</button>
                        <button>Supprimer</button>
                    </td>
                </tr>
                <!-- Autres commandes -->
            </tbody>
        </table>
    </div>
</body>
</html>











<!--
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #007BFF;
            color: #fff;
            padding: 1em 0;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: #fff;
        }
        button {
            padding: 8px 12px;
            margin: 5px 0;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Gestion des Commandes</h1>
    </header>
    <div class="container">
        <h2>Liste des Commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Jean Dupont</td>
                    <td>Ordinateur</td>
                    <td>2</td>
                    <td>2025-04-25</td>
                    <td><button>Modifier</button> <button>Supprimer</button></td>
                </tr>
                Ajouter d'autres commandes ici 
            </tbody>
        </table>
    </div>
</body>
</html> -->
