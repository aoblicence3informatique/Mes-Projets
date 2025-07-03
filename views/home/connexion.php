<?php
session_start();
require_once '../../config/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND statut = "actif"');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'tresorier') {
            $_SESSION['tresorier_id'] = $user['id'];
            header('Location: ../tresorier/index.php');
            exit;
        } else {
            $error = "Identifiants incorrects, veuillez revoir vos identifiants.";
        }
    } else {
        $error = "Identifiants incorrects, veuillez revoir vos identifiants.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Trésorier - AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-100 via-white to-blue-100 min-h-screen flex flex-col items-center justify-center py-16">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-10">
        <h1 class="text-2xl font-extrabold text-yellow-700 mb-6 text-center">Connexion Trésorier</h1>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center font-semibold"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-6">
            <div>
                <label class="block font-bold mb-1">Email</label>
                <input type="email" name="email" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-yellow-300">
            </div>
            <div>
                <label class="block font-bold mb-1">Mot de passe</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-yellow-300 pr-10">
                    <button type="button" id="togglePassword" class="absolute right-2 top-2 text-yellow-500 focus:outline-none" tabindex="-1">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.362-2.675A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.03M15 12a3 3 0 11-6 0 3 3 0 016 0zm-6 0l6 6m0-6l-6 6" /></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded shadow transition">Se connecter</button>
        </form>
    </div>
</body>
<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeOpen = document.getElementById('eyeOpen');
const eyeClosed = document.getElementById('eyeClosed');
if (togglePassword) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    });
}
</script>
</html>
