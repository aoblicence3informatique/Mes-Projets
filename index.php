<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Entête façon Amazon, sans barre de recherche, avec menu enrichi -->
    <header class="bg-gradient-to-r from-yellow-400 to-yellow-600 shadow-md sticky top-0 z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-3 px-6">
            <div class="flex items-center space-x-3">
                <img src="public/Image/Screenshot_20250702-114348_1.png" alt="Logo AJVDNK" class="h-14 w-14 rounded-full border-4 border-yellow-300 shadow-lg bg-white object-cover transition-transform duration-200 hover:scale-105">
                <span class="text-2xl font-extrabold text-gray-900 tracking-wide">AJVDNK</span>
            </div>
            <nav class="flex space-x-8 text-base font-semibold">
                <a href="index.php" class="text-gray-900 hover:text-yellow-900 transition">Accueil</a>
                <a href="views/home/apropos.php" class="text-gray-900 hover:text-yellow-900 transition">A Propos</a>
                <a href="views/home/historique.php" class="text-gray-900 hover:text-yellow-900 transition">Historique</a>
                <a href="views/home/contact.php" class="text-gray-900 hover:text-yellow-900 transition">Contact</a>
                <a href="views/home/connexion.php" class="text-gray-900 hover:text-yellow-900 transition">Connexion</a>
            </nav>
        </div>
    </header>

    <!-- Bannière centrale immersive -->
    <main class="flex-1 flex flex-col items-center justify-center py-12">
        <div class="w-full max-w-5xl bg-gradient-to-r from-blue-700 via-blue-500 to-blue-300 rounded-2xl shadow-2xl p-12 flex flex-col md:flex-row items-center justify-between mb-10 relative overflow-hidden">
            <div class="mb-8 md:mb-0 md:w-2/3 z-10">
                <h1 class="text-5xl font-extrabold text-white mb-4 drop-shadow-lg">Bienvenue sur <span class="text-yellow-300">AJVDNK</span></h1>
                <p class="text-xl text-blue-100 mb-6">La plateforme moderne pour gérer vos membres, cotisations et dons en toute simplicité, sécurité et transparence.</p>
                <a href="views/tresorier/liste_membres.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-8 py-4 rounded shadow-lg transition text-lg">Commencer maintenant</a>
            </div>
            <img src="Public/Image/1751544591402_1.jpg" alt="Bannière AJVDNK" class="rounded-xl shadow-2xl w-80 h-52 object-cover md:ml-8 border-4 border-yellow-300 z-10">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-yellow-400 rounded-full opacity-30 blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white rounded-full opacity-20 blur-2xl"></div>
        </div>
        <!-- Section vidéos en français (grille responsive) -->
        <section class="w-full max-w-6xl flex flex-col md:flex-row gap-8 items-stretch justify-center mt-8">
            <div class="flex-1 bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                <iframe class="rounded-lg w-full h-64 mb-4" src="https://www.youtube.com/embed/T4mxZ2bazSA" title="Vidéo recommandée par vous" frameborder="0" allowfullscreen></iframe>
                <h2 class="font-bold text-lg mb-2 text-blue-700">Vidéo recommandée par vous</h2>
                <p class="text-gray-600 text-center">Cette vidéo a été intégrée selon votre demande.</p>
            </div>
            <div class="flex-1 bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                <iframe class="rounded-lg w-full h-64 mb-4" src="https://www.youtube.com/embed/liPgMfEXm1w" title="Short - Vidéo utilisateur" frameborder="0" allowfullscreen></iframe>
                <h2 class="font-bold text-lg mb-2 text-green-700">Vidéo recommandée par vous</h2>
                <p class="text-gray-600 text-center">Une vidéo YouTube Short en français, intégrée selon votre demande.</p>
            </div>
            <div class="flex-1 bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                <iframe class="rounded-lg w-full h-64 mb-4" src="https://www.youtube.com/embed/lwOWt9CN78k" title="Vidéo recommandée par vous" frameborder="0" allowfullscreen></iframe>
                <h2 class="font-bold text-lg mb-2 text-purple-700">Vidéo recommandée par vous</h2>
                <p class="text-gray-600 text-center">Cette vidéo a été intégrée selon votre demande.</p>
            </div>
        </section>
    </main>

    <!-- Pied de page façon Amazon -->
    <footer class="bg-gray-900 text-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="font-bold mb-2">AJVDNK</h3>
                <ul>
                    <li><a href="index.php" class="hover:underline">Accueil</a></li>
                    <li><a href="views/home/apropos.php" class="hover:underline">À propos</a></li>
                    <li><a href="views/home/contact.php" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Téléphone</h3>
                <ul>
                    <li><a href="#" class="hover:underline">+224 627 98 06 95</a></li>
                    <li><a href="#" class="hover:underline">+224 624 89 57 07</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Email</h3>
                <ul>
                    <li><a href="#" class="hover:underline">baldealfomar134@gmail.com</a></li>
                    <li><a href="#" class="hover:underline">bahoumar959827@gmail.com</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Suivez-nous</h3>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:text-yellow-400">Facebook</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Twitter</a></li>
                    <li><a href="#" class="hover:text-yellow-400">LinkedIn</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-gray-400 text-xs py-4 border-t border-gray-700">
            © 2025 AJVDNK - L'effort fait les forts | Tous droits réservés
        </div>
    </footer>
</body>
</html>
