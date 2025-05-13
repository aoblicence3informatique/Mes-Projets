<?php
    include_once("connexion.php");
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tableau de Bord</title>
</head>
<body>
    <header>
        <h1>Gestion des Commandes</h1>
    </header>
    <nav>
        <ul>
            <li><a href="clients.html">Clients</a></li>
            <li><a href="commandes.html">Commandes</a></li>
            <li><a href="articles.html">Articles</a></li>
        </ul>
    </nav>
    <div class="dashboard">
        <div class="card">
            <h2>Clients</h2>
            <p>Total : 120</p>
            <a href="clients.html" class="button">Voir Détails</a>
        </div>
        <div class="card">
            <h2>Commandes</h2>
            <p>En cours : 45</p>
            <a href="commandes.html" class="button">Voir Détails</a>
        </div>
        <div class="card">
            <h2>Articles</h2>
            <p>Stock disponible : 300</p>
            <a href="articles.html" class="button">Voir Détails</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Gestion des Commandes - Tous droits réservés</p>
    </footer>
</body>
</html>
