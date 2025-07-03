<?php
require_once '../../config/config.php';

// Récupérer l'id du membre à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste_membres.php');
    exit();
}
$id = (int)$_GET['id'];

// Récupérer les infos du membre
$stmt = $pdo->prepare('SELECT * FROM members WHERE id = ?');
$stmt->execute([$id]);
$membre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$membre) {
    header('Location: liste_membres.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $contact = trim($_POST['contact']);
    $sexe = $_POST['sexe'];
    $statut = $_POST['statut'];
    if ($nom && $prenom && $sexe && $statut) {
        $stmt = $pdo->prepare('UPDATE members SET nom=?, prenom=?, contact=?, sexe=?, statut=? WHERE id=?');
        if ($stmt->execute([$nom, $prenom, $contact, $sexe, $statut, $id])) {
            header('Location: liste_membres.php?updated=1');
            exit();
        } else {
            $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Erreur lors de la modification.</div>';
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
    <title>Modifier un membre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Modifier un membre</h1>
        <?= $message ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="nom" class="block text-gray-700 font-bold mb-2">Nom <span class="text-red-500">*</span></label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($membre['nom']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="prenom" class="block text-gray-700 font-bold mb-2">Prénom <span class="text-red-500">*</span></label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($membre['prenom']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="contact" class="block text-gray-700 font-bold mb-2">Contact</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($membre['contact']) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="sexe" class="block text-gray-700 font-bold mb-2">Sexe <span class="text-red-500">*</span></label>
                <select id="sexe" name="sexe" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="homme" <?= $membre['sexe']==='homme'?'selected':'' ?>>Homme</option>
                    <option value="femme" <?= $membre['sexe']==='femme'?'selected':'' ?>>Femme</option>
                </select>
            </div>
            <div>
                <label for="statut" class="block text-gray-700 font-bold mb-2">Statut <span class="text-red-500">*</span></label>
                <select id="statut" name="statut" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="actif" <?= $membre['statut']==='actif'?'selected':'' ?>>Actif</option>
                    <option value="inactif" <?= $membre['statut']==='inactif'?'selected':'' ?>>Inactif</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 text-white font-bold px-6 py-3 rounded shadow transition">Enregistrer les modifications</button>
        </form>
        <div class="mt-6 text-center">
            <a href="liste_membres.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-2 rounded shadow transition">Retour à la liste</a>
        </div>
    </div>
</body>
</html>
