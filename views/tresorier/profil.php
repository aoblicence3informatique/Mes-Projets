<?php
require_once '../../config/config.php';
session_start();
if (!isset($_SESSION['tresorier_id'])) {
    header('Location: ../home/index.php');
    exit;
}
$tresorier_id = $_SESSION['tresorier_id'];
$stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ? AND role = "tresorier"');
$stmt->execute([$tresorier_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo '<div class="text-red-600 font-bold p-6">Trésorier introuvable.</div>';
    exit;
}
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];
    if ($username && $email) {
        $params = [$username, $email];
        $sql = 'UPDATE users SET username = ?, email = ?';
        if ($mdp) {
            $sql .= ', password = ?';
            $params[] = password_hash($mdp, PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id = ? AND role = "tresorier"';
        $params[] = $tresorier_id;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $success = true;
            // Recharger les données utilisateur depuis la base pour afficher les valeurs à jour
            $stmt2 = $pdo->prepare('SELECT username, email FROM users WHERE id = ? AND role = "tresorier"');
            $stmt2->execute([$tresorier_id]);
            $user = $stmt2->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Erreur lors de la mise à jour.';
        }
    } else {
        $error = 'Nom et email obligatoires.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-blue-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10 border border-yellow-200">
        <div class="flex items-center mb-8 gap-6">
            <img src="../../public/Image/Screenshot_20250702-114348_1.png" alt="Avatar" class="h-24 w-24 rounded-full border-4 border-yellow-400 shadow-lg bg-white object-cover">
            <div>
                <h1 class="text-3xl font-extrabold text-yellow-700 mb-1">Mon profil</h1>
                <p class="text-gray-600">Gérez vos informations personnelles et modifiez votre mot de passe.</p>
            </div>
        </div>
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center font-semibold">Profil mis à jour avec succès !</div>
        <?php elseif ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center font-semibold"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block font-bold mb-1 text-gray-700">Nom d'utilisateur</label>
                    <input type="text" name="username" required class="w-full border-2 border-yellow-200 rounded px-4 py-2 focus:ring-2 focus:ring-yellow-300 text-lg" value="<?= htmlspecialchars($user['username']) ?>">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-gray-700">Email</label>
                    <input type="email" name="email" required class="w-full border-2 border-yellow-200 rounded px-4 py-2 focus:ring-2 focus:ring-yellow-300 text-lg" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
            </div>
            <div class="md:w-1/2">
                <label class="block font-bold mb-1 text-gray-700">Nouveau mot de passe <span class="text-gray-400 font-normal">(laisser vide pour ne pas changer)</span></label>
                <div class="relative">
                    <input type="password" name="mdp" id="mdp" class="w-full border-2 border-yellow-200 rounded px-4 py-2 focus:ring-2 focus:ring-yellow-300 text-lg pr-10">
                    <button type="button" id="toggleMdp" class="absolute right-2 top-2 text-yellow-500 focus:outline-none" tabindex="-1">
                        <svg id="eyeOpenMdp" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg id="eyeClosedMdp" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.362-2.675A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.03M15 12a3 3 0 11-6 0 3 3 0 016 0zm-6 0l6 6m0-6l-6 6" /></svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center mt-8">
                <a href="index.php" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-6 py-2 rounded shadow transition">&larr; Retour au dashboard</a>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-8 py-2 rounded shadow-lg transition text-lg">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</body>
</html>
<script>
const toggleMdp = document.getElementById('toggleMdp');
const mdpInput = document.getElementById('mdp');
const eyeOpenMdp = document.getElementById('eyeOpenMdp');
const eyeClosedMdp = document.getElementById('eyeClosedMdp');
if (toggleMdp) {
    toggleMdp.addEventListener('click', function() {
        const type = mdpInput.getAttribute('type') === 'password' ? 'text' : 'password';
        mdpInput.setAttribute('type', type);
        eyeOpenMdp.classList.toggle('hidden');
        eyeClosedMdp.classList.toggle('hidden');
    });
}
</script>
