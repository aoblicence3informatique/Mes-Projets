<?php
require_once '../../config/config.php';

// Récupérer l'id du paiement à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste_paiements.php');
    exit();
}
$id = (int)$_GET['id'];

// Récupérer les infos du paiement
$stmt = $pdo->prepare('SELECT * FROM contributions WHERE id = ?');
$stmt->execute([$id]);
$paiement = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$paiement) {
    header('Location: liste_paiements.php');
    exit();
}

// Liste des membres actifs
$membres = $pdo->query('SELECT id, nom, prenom FROM members WHERE statut = "actif" ORDER BY nom, prenom')->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membre_id = (int)$_POST['membre_id'];
    $montant = trim($_POST['montant']);
    $type = $_POST['type'];
    $date_cotisation = $_POST['date_cotisation'];
    if ($membre_id && $montant && $type && $date_cotisation) {
        $stmt = $pdo->prepare('UPDATE contributions SET membre_id=?, montant=?, type=?, date_cotisation=? WHERE id=?');
        if ($stmt->execute([$membre_id, $montant, $type, $date_cotisation, $id])) {
            header('Location: liste_paiements.php?updated=1');
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
    <title>Modifier un paiement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Modifier un paiement</h1>
        <?= $message ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="membre_id" class="block text-gray-700 font-bold mb-2">Membre <span class="text-red-500">*</span></label>
                <select id="membre_id" name="membre_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">-- Sélectionner un membre --</option>
                    <?php foreach ($membres as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $paiement['membre_id']==$m['id']?'selected':'' ?>><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="montant" class="block text-gray-700 font-bold mb-2">Montant <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" id="montant" name="montant" value="<?= htmlspecialchars($paiement['montant']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label for="type" class="block text-gray-700 font-bold mb-2">Type de cotisation <span class="text-red-500">*</span></label>
                <select id="type" name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="hebdomadaire" <?= $paiement['type']==='hebdomadaire'?'selected':'' ?>>Hebdomadaire</option>
                    <option value="mensuel" <?= $paiement['type']==='mensuel'?'selected':'' ?>>Mensuel</option>
                </select>
            </div>
            <div>
                <label for="date_cotisation" class="block text-gray-700 font-bold mb-2">Date de paiement <span class="text-red-500">*</span></label>
                <input type="date" id="date_cotisation" name="date_cotisation" value="<?= htmlspecialchars($paiement['date_cotisation']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 text-white font-bold px-6 py-3 rounded shadow transition">Enregistrer les modifications</button>
        </form>
        <div class="mt-6 text-center">
            <a href="liste_paiements.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-2 rounded shadow transition">Retour à la liste</a>
        </div>
    </div>
</body>
</html>
