<?php
require_once '../../config/config.php';

// Récupération de l'ID du don à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste_dons.php');
    exit();
}
$id = (int)$_GET['id'];

// Récupération des infos du don
$stmt = $pdo->prepare('SELECT * FROM donations WHERE id = ?');
$stmt->execute([$id]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$don) {
    header('Location: liste_dons.php');
    exit();
}

$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donateur = isset($_POST['donateur']) ? trim($_POST['donateur']) : '';
    $montant = isset($_POST['montant']) ? floatval($_POST['montant']) : 0;
    $date_don = isset($_POST['date_don']) ? $_POST['date_don'] : '';
    $motif = isset($_POST['motif']) ? trim($_POST['motif']) : '';
    $mode_paiement = isset($_POST['mode_paiement']) ? $_POST['mode_paiement'] : 'especes';

    if ($donateur === '' || $montant <= 0 || $date_don === '') {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        $stmt = $pdo->prepare('UPDATE donations SET donateur = ?, montant = ?, date_don = ?, motif = ?, mode_paiement = ? WHERE id = ?');
        $stmt->execute([$donateur, $montant, $date_don, $motif, $mode_paiement, $id]);
        header('Location: liste_dons.php?success=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un don</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-2xl font-extrabold text-green-700 mb-6 text-center">Modifier un don</h1>
        <?php if ($erreur): ?>
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 text-center"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-6">
            <div>
                <label class="block font-bold mb-1">Donateur <span class="text-red-500">*</span></label>
                <input type="text" name="donateur" value="<?= htmlspecialchars($don['donateur']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label class="block font-bold mb-1">Montant (GNF) <span class="text-red-500">*</span></label>
                <input type="number" name="montant" min="1" step="0.01" value="<?= htmlspecialchars($don['montant']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label class="block font-bold mb-1">Date du don <span class="text-red-500">*</span></label>
                <input type="date" name="date_don" value="<?= htmlspecialchars($don['date_don']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label class="block font-bold mb-1">Motif</label>
                <input type="text" name="motif" value="<?= htmlspecialchars($don['motif']) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label class="block font-bold mb-1">Mode de paiement</label>
                <select name="mode_paiement" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="especes" <?= ($don['mode_paiement'] === 'especes' || $don['mode_paiement'] === '' ? 'selected' : '') ?>>Espèces</option>
                    <option value="orange_money" <?= ($don['mode_paiement'] === 'orange_money' ? 'selected' : '') ?>>Orange Money</option>
                </select>
            </div>
            <div class="flex justify-between items-center mt-8">
                <a href="liste_dons.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-6 py-2 rounded shadow transition">Annuler</a>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white font-bold px-6 py-2 rounded shadow transition">Enregistrer</button>
            </div>
        </form>
    </div>
</body>
</html>
