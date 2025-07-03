<?php
require_once '../../config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membre_id = isset($_POST['membre_id']) ? (int)$_POST['membre_id'] : 0;
    $montant = isset($_POST['montant']) ? trim($_POST['montant']) : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $date_cotisation = isset($_POST['date_cotisation']) ? $_POST['date_cotisation'] : date('Y-m-d');
    $mode_paiement = isset($_POST['mode_paiement']) ? $_POST['mode_paiement'] : '';
    if ($membre_id && $montant && $type && $date_cotisation && $mode_paiement) {
        $stmt = $pdo->prepare('INSERT INTO contributions (membre_id, montant, type, date_cotisation, mode_paiement) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$membre_id, $montant, $type, $date_cotisation, $mode_paiement])) {
            header('Location: liste_paiements.php?success=1');
            exit();
        } else {
            $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Erreur lors de l\'ajout du paiement.</div>';
        }
    } else {
        $message = '<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">Veuillez remplir tous les champs obligatoires.</div>';
    }
}
// Récupérer la liste des membres actifs
$membres = $pdo->query('SELECT id, nom, prenom FROM members WHERE statut = "actif" ORDER BY nom, prenom')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un paiement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-yellow-700 mb-6 text-center">Ajouter un paiement</h1>
        <?= $message ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="membre_id" class="block text-gray-700 font-bold mb-2">Membre <span class="text-red-500">*</span></label>
                <select id="membre_id" name="membre_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Sélectionner un membre --</option>
                    <?php foreach ($membres as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="montant" class="block text-gray-700 font-bold mb-2">Montant <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="montant" name="montant" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="type" class="block text-gray-700 font-bold mb-2">Type de cotisation <span class="text-red-500">*</span></label>
                <select id="type" name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Sélectionner --</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                    <option value="mensuel">Mensuel</option>
                </select>
            </div>
            <div>
                <label for="date_cotisation" class="block text-gray-700 font-bold mb-2">Date de paiement <span class="text-red-500">*</span></label>
                <input type="date" id="date_cotisation" name="date_cotisation" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="mode_paiement" class="block text-gray-700 font-bold mb-2">Mode de paiement <span class="text-red-500">*</span></label>
                <select id="mode_paiement" name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Sélectionner --</option>
                    <option value="especes">Espèces</option>
                    <option value="orange_money">Orange Money</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-3 rounded shadow transition">Ajouter le paiement</button>
        </form>
    </div>
</body>
</html>
