<?php
// Formulaire d'ajout de membre
require_once '../../config/config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
    $statut = isset($_POST['statut']) ? $_POST['statut'] : 'actif';
    if ($nom && $prenom && $sexe) {
        $stmt = $pdo->prepare('INSERT INTO members (nom, prenom, contact, sexe, statut) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$nom, $prenom, $contact, $sexe, $statut])) {
            // Redirection après succès
            header('Location: index.php?success=1');
            exit();
        } else {
            $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Erreur lors de l\'ajout du membre.</div>';
        }
    } else {
        $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Veuillez remplir tous les champs obligatoires.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un membre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-yellow-700 mb-6 text-center">Ajouter un membre</h1>
        <?= $message ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="nom" class="block text-gray-700 font-bold mb-2">Nom <span class="text-red-500">*</span></label>
                <input type="text" id="nom" name="nom" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="prenom" class="block text-gray-700 font-bold mb-2">Prénom <span class="text-red-500">*</span></label>
                <input type="text" id="prenom" name="prenom" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="contact" class="block text-gray-700 font-bold mb-2">Contact</label>
                <input type="text" id="contact" name="contact" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="sexe" class="block text-gray-700 font-bold mb-2">Sexe <span class="text-red-500">*</span></label>
                <select id="sexe" name="sexe" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Sélectionner --</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div>
            <input type="hidden" name="statut" value="actif">
            <div class="text-sm text-gray-500 italic">Le statut du membre sera automatiquement <span class="font-bold text-green-600">actif</span> lors de l'inscription.</div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-3 rounded shadow transition">Ajouter le membre</button>
        </form>
    </div>
</body>
</html>
