<?php
// Connexion simple à la base de données MySQL avec PDO

$host = 'localhost';
$dbname = 'ajvdk_finance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Facultatif : définir le mode d'erreur sur Exception
    // echo "Connexion réussie à la base de données !";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
