<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Trésorier - AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 via-white to-gray-200 min-h-screen flex flex-col">
    <header class="backdrop-blur bg-white/80 shadow-md sticky top-0 z-30 border-b border-yellow-200">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-2 px-8">
            <div class="flex items-center space-x-4">
                <img src="../../public/Image/Screenshot_20250702-114348_1.png" alt="Logo AJVDNK" class="h-12 w-12 rounded-full border-2 border-yellow-400 bg-white shadow object-cover">
                <span class="text-2xl font-bold text-gray-800 tracking-wider">AJVDNK</span>
            </div>
            <nav class="flex-1 flex justify-center space-x-8 text-base font-medium relative">
                <div class="relative" id="dropdown-cotisation-container">
                    <a href="#" id="dropdown-cotisation-btn" class="text-gray-700 hover:text-blue-600 transition flex items-center gap-1">Cotisation
                        <svg class="w-4 h-4 mt-0.5 text-gray-500 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div id="dropdown-cotisation-menu" class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible pointer-events-none transition-all z-30">
                        <a href="ajout_paiement.php" class="block px-5 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-t-lg transition">Ajouter un Paiement</a>
                        <a href="liste_paiements.php" class="block px-5 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-b-lg transition">Liste des Paiements</a>
                    </div>
                </div>
                <div class="relative" id="dropdown-membre-container">
                    <a href="#" id="dropdown-membre-btn" class="text-gray-700 hover:text-yellow-600 transition flex items-center gap-1">Membre
                        <svg class="w-4 h-4 mt-0.5 text-gray-500 group-hover:text-yellow-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div id="dropdown-membre-menu" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible pointer-events-none transition-all z-30">
                        <a href="ajout_membre.php" class="block px-5 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-t-lg transition">Ajout d'un Membre</a>
                        <a href="liste_membres.php" class="block px-5 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-b-lg transition">Liste des Membres</a>
                    </div>
                </div>
                <div class="relative" id="dropdown-don-container">
                    <a href="#" id="dropdown-don-btn" class="text-gray-700 hover:text-green-600 transition flex items-center gap-1">Don
                        <svg class="w-4 h-4 mt-0.5 text-gray-500 group-hover:text-green-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div id="dropdown-don-menu" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible pointer-events-none transition-all z-30">
                        <a href="ajout_don.php" class="block px-5 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-t-lg transition">Ajouter un Don</a>
                        <a href="liste_dons.php" class="block px-5 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-b-lg transition">Liste des Dons</a>
                    </div>
                </div>
                <a href="rapport.php" class="text-gray-700 hover:text-purple-600 transition">Rapport</a>
                <a href="statistique.php" class="text-gray-700 hover:text-pink-600 transition">Statistique</a>
                <a href="profil.php" class="text-gray-700 hover:text-blue-600 transition">Profil</a>
            </nav>
            <div class="flex items-center space-x-2">
                <a href="logout.php" class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"></path></svg>
                    <span>Se déconnecter</span>
                </a>
            </div>
        </div>
    </header>
    <main class="flex-1 flex flex-col items-center justify-center py-16">
        <div class="w-full max-w-3xl mx-auto mb-10">
            <div class="bg-white/80 backdrop-blur rounded-2xl shadow-lg p-10 flex flex-col md:flex-row items-center justify-between border-l-4 border-yellow-200">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Bienvenue dans l’espace Trésorier</h3>
                    <p class="text-gray-600">Gérez tous les aspects financiers de l’association AJVDNK depuis ce tableau de bord moderne et intuitif.</p>
                </div>
            </div>
        </div>
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
            <!-- Bloc Membre -->
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow-xl p-10 flex flex-col items-center border border-gray-200 hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                <svg class="w-14 h-14 text-yellow-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4v2a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"></path></svg>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Gestion des membres</h2>
                <p class="text-gray-500 mb-4 text-center">Ajoutez, modifiez ou consultez les membres de l’association.</p>
                <a href="liste_membres.php" class="bg-yellow-400/80 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-2 rounded shadow transition">Voir les membres</a>
            </div>
            <!-- Bloc Cotisation -->
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow-xl p-10 flex flex-col items-center border border-gray-200 hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                <svg class="w-14 h-14 text-blue-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 16v-4m8-4h-4m-8 0H4"></path></svg>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Cotisations</h2>
                <p class="text-gray-500 mb-4 text-center">Gérez les cotisations, enregistrez les paiements et visualisez l’état des contributions.</p>
                <a href="liste_paiements.php" class="bg-blue-400/80 hover:bg-blue-500 text-white font-bold px-6 py-2 rounded shadow transition">Voir les cotisations</a>
            </div>
            <!-- Bloc Don -->
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow-xl p-10 flex flex-col items-center border border-gray-200 hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                <svg class="w-14 h-14 text-green-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 16v-4m8-4h-4m-8 0H4"></path></svg>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Gestion des dons</h2>
                <p class="text-gray-500 mb-4 text-center">Suivez les dons reçus et gérez les donateurs.</p>
                <a href="liste_dons.php" class="bg-green-400/80 hover:bg-green-500 text-white font-bold px-6 py-2 rounded shadow transition">Voir les dons</a>
            </div>
            <!-- Bloc Rapport -->
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow-xl p-10 flex flex-col items-center border border-gray-200 hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                <svg class="w-14 h-14 text-purple-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Rapports</h2>
                <p class="text-gray-500 mb-4 text-center">Consultez ou exportez les rapports financiers et d’activité.</p>
                <a href="rapport.php" class="bg-purple-400/80 hover:bg-purple-500 text-white font-bold px-6 py-2 rounded shadow transition">Voir les rapports</a>
            </div>
            <!-- Bloc Statistique -->
            <div class="bg-white/70 backdrop-blur rounded-2xl shadow-xl p-10 flex flex-col items-center border border-gray-200 hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                <svg class="w-14 h-14 text-pink-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2v10zm-6 0a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10z"></path></svg>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Statistiques</h2>
                <p class="text-gray-500 mb-4 text-center">Visualisez les statistiques financières et graphiques.</p>
                <a href="statistique.php" class="bg-pink-400/80 hover:bg-pink-500 text-white font-bold px-6 py-2 rounded shadow transition">Voir les statistiques</a>
            </div>
        </div>
    </main>
    <footer class="bg-gray-900 text-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="font-bold mb-2">AJVDNK</h3>
                <ul>
                    <li><a href="index.php" class="hover:underline">Accueil</a></li>
                    <li><a href="liste_membres.php" class="hover:underline">Membre</a></li>
                    <li><a href="liste_paiements.php" class="hover:underline">Cotisation</a></li>
                    <li><a href="liste_dons.php" class="hover:underline">Don</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Fonctionnalités</h3>
                <ul>
                    <li><a href="rapport.php" class="hover:underline">Rapport</a></li>
                    <li><a href="statistique.php" class="hover:underline">Statistique</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Ressources</h3>
                <ul>
                    <li><a href="aide_utilisation.html" target="_blank" class="hover:underline">Aide d’utilisation</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-2">Suivi</h3>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:text-yellow-400">Facebook</a></li>
                    <li><a href="#" class="hover:text-yellow-400">WhatsApp</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Email</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-gray-400 text-xs py-4 border-t border-gray-700">
            © 2025 AJVDNK - Espace Trésorier | Tous droits réservés
        </div>
    </footer>
    <!-- Modale d'aide d'utilisation -->
    <div id="helpModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8 relative animate-fade-in">
            <button id="closeHelpModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
            <h2 class="text-2xl font-extrabold text-yellow-700 mb-4">Aide d’utilisation de l’espace Trésorier AJVDNK</h2>
            <div class="text-gray-700 space-y-4 text-base leading-relaxed max-h-[60vh] overflow-y-auto">
                <ol class="list-decimal pl-6 space-y-2">
                    <li>
                        <strong>Connexion sécurisée</strong><br>
                        Accédez à l’application via la page de connexion. Saisissez votre nom d’utilisateur et mot de passe. En cas d’oubli, contactez l’administrateur pour réinitialiser votre accès.
                    </li>
                    <li>
                        <strong>Tableau de bord</strong><br>
                        Après connexion, le tableau de bord vous accueille avec un résumé des principales fonctionnalités : gestion des membres, cotisations, dons, rapports et statistiques.
                    </li>
                    <li>
                        <strong>Gestion des membres</strong><br>
                        • <b>Ajouter un membre</b> : Utilisez le menu « Membre » &rarr; « Ajout d’un Membre » pour enregistrer un nouveau membre.<br>
                        • <b>Liste des membres</b> : Consultez, recherchez, modifiez ou supprimez les membres existants via « Liste des Membres ».
