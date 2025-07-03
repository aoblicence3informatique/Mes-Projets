<?php
require_once '../../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donateur = isset($_POST['donateur']) && trim($_POST['donateur']) !== '' ? trim($_POST['donateur']) : 'Anonyme';
    $montant = isset($_POST['montant']) ? trim($_POST['montant']) : '';
    $date_don = isset($_POST['date_don']) ? $_POST['date_don'] : date('Y-m-d');
    $motif = isset($_POST['motif']) ? trim($_POST['motif']) : '';
    $mode_paiement = isset($_POST['mode_paiement']) ? $_POST['mode_paiement'] : '';
    if ($montant && $date_don && $mode_paiement) {
        $stmt = $pdo->prepare('INSERT INTO donations (donateur, montant, date_don, motif, mode_paiement) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$donateur, $montant, $date_don, $motif, $mode_paiement])) {
            header('Location: liste_dons.php?success=1');
            exit();
        } else {
            $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Erreur lors de l\'ajout du don.</div>';
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
    <title>Ajouter un don</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-green-700 mb-6 text-center">Ajouter un don</h1>
        <?= $message ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="donateur" class="block text-gray-700 font-bold mb-2">Donateur</label>
                <input type="text" id="donateur" name="donateur" placeholder="Nom du donateur (optionnel)" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label for="montant" class="block text-gray-700 font-bold mb-2">Montant <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="montant" name="montant" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label for="date_don" class="block text-gray-700 font-bold mb-2">Date du don <span class="text-red-500">*</span></label>
                <input type="date" id="date_don" name="date_don" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label for="motif" class="block text-gray-700 font-bold mb-2">Motif</label>
                <textarea id="motif" name="motif" rows="2" placeholder="Motif du don (optionnel)" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
            </div>
            <div>
                <label for="mode_paiement" class="block text-gray-700 font-bold mb-2">Mode de paiement <span class="text-red-500">*</span></label>
                <select id="mode_paiement" name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="">-- Sélectionner --</option>
                    <option value="especes">Espèces</option>
                    <option value="orange_money">Orange Money</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-green-400 hover:bg-green-500 text-white font-bold px-6 py-3 rounded shadow transition">Ajouter le don</button>
        </form>
    </div>
</body>
</html>
