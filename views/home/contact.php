<?php
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    if ($nom && $email && $message) {
        $to = 'baldealfomar134@gmail.com';
        $subject = "Nouveau message de contact AJVDNK";
        $body = "Nom: $nom\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email\r\nReply-To: $email";
        if (mail($to, $subject, $body, $headers)) {
            $success = true;
        } else {
            $error = "Erreur lors de l'envoi. Veuillez réessayer.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-100 via-white to-green-100 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-lg bg-white/90 backdrop-blur rounded-2xl shadow-2xl p-10 mt-10 mb-10 border border-yellow-200">
        <a href="../../index.php" class="inline-block mb-6 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-bold px-6 py-2 rounded shadow transition">&larr; Retour à l'accueil</a>
        <h1 class="text-2xl font-extrabold text-yellow-700 mb-6 text-center tracking-wide">Contactez l'association AJVDNK</h1>
        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center font-semibold">Votre message a bien été envoyé. Merci !</div>
        <?php elseif ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center font-semibold"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-6">
            <div>
                <label class="block font-bold mb-1 text-gray-700">Nom</label>
                <input type="text" name="nom" required class="w-full border border-yellow-200 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-300 bg-yellow-50" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full border border-yellow-200 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-300 bg-yellow-50" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Message</label>
                <textarea name="message" required rows="5" class="w-full border border-yellow-200 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-300 bg-yellow-50"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 rounded shadow transition">Envoyer</button>
        </form>
        <div class="mt-8 text-center">
            <a id="wa-link" href="https://wa.me/224624895707" target="_blank" class="inline-flex items-center gap-2 px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-full font-semibold shadow transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.97L0 24l6.22-1.63A12.07 12.07 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.21-1.25-6.23-3.48-8.52zM12 22c-1.85 0-3.66-.5-5.22-1.44l-.37-.22-3.69.97.99-3.59-.24-.37A9.94 9.94 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.8c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.28-.48.09-.19.05-.36-.02-.5-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.35-.01-.54-.01-.19 0-.5.07-.76.34-.26.27-1 1-1 2.43s1.02 2.82 1.16 3.02c.14.2 2.01 3.08 4.88 4.2.68.29 1.21.46 1.62.59.68.22 1.3.19 1.79.12.55-.08 1.65-.67 1.88-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.19-.53-.33z"/></svg>
                Contacter sur WhatsApp
            </a>
        </div>
    </div>
    <script>
    // Pré-remplir le message WhatsApp avec les infos du formulaire
    document.addEventListener('DOMContentLoaded', function() {
        var waLink = document.getElementById('wa-link');
        var form = document.querySelector('form');
        if (waLink && form) {
            form.addEventListener('input', function() {
                var nom = form.nom.value.trim();
                var email = form.email.value.trim();
                var msg = form.message.value.trim();
                var txt = '';
                if(nom || email || msg) {
                    txt = 'Bonjour, je suis ' + (nom ? nom : '[Nom]') + '%0AEmail: ' + (email ? email : '[Email]') + '%0A' + (msg ? msg : '[Votre message]');
                }
                waLink.href = 'https://wa.me/224624895707?text=' + encodeURIComponent(txt);
            });
        }
    });
    </script>
</body>
</html>
