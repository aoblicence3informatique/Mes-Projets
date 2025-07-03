<?php
require_once '../../config/config.php';

// Suppression d'un don
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM donations WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: liste_dons.php?deleted=1');
    exit();
}

// Recherche par donateur
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM donations WHERE donateur LIKE ? ORDER BY id DESC');
    $stmt->execute(['%' . $search . '%']);
    $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query('SELECT * FROM donations ORDER BY id DESC');
    $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calcul du total des dons par motif (regroupement insensible à la casse et aux espaces)
$totaux_motif = [];
$motif_labels = [];
foreach ($dons as $d) {
    $motif_clean = strtolower(trim($d['motif']));
    $motif_clean = $motif_clean !== '' ? $motif_clean : 'sans motif';
    if (!isset($totaux_motif[$motif_clean])) {
        $totaux_motif[$motif_clean] = 0;
        $motif_labels[$motif_clean] = [];
    }
    $totaux_motif[$motif_clean] += $d['montant'];
    $motif_labels[$motif_clean][] = $d['motif'] !== '' ? $d['motif'] : 'Sans motif';
}
// Pour l'affichage, on prend le motif d'origine le plus fréquent pour chaque groupe
function motif_label_majoritaire($labels) {
    $counts = array_count_values($labels);
    arsort($counts);
    return key($counts);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des dons</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-green-700 mb-6 text-center">Liste des dons</h1>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <form method="get" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par donateur..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 w-full md:w-64">
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white font-bold px-4 py-2 rounded shadow transition">Rechercher</button>
            </form>
            <?php if ($search !== ''): ?>
                <a href="liste_dons.php" class="text-blue-600 hover:underline text-sm">Réinitialiser la recherche</a>
            <?php endif; ?>
        </div>
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">Don ajouté avec succès.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">Don supprimé avec succès.</div>
        <?php endif; ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-3 border-b text-left">#</th>
                        <th class="px-4 py-3 border-b text-left">Donateur</th>
                        <th class="px-4 py-3 border-b text-left">Montant</th>
                        <th class="px-4 py-3 border-b text-left">Date</th>
                        <th class="px-4 py-3 border-b text-left">Motif</th>
                        <th class="px-4 py-3 border-b text-left">Mode</th>
                        <th class="px-4 py-3 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($dons) > 0): ?>
                    <?php foreach ($dons as $d): ?>
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($d['id']) ?></td>
                            <td class="px-4 py-2 border-b font-semibold text-gray-800"><?= htmlspecialchars($d['donateur']) ?></td>
                            <td class="px-4 py-2 border-b font-bold text-green-700"><?= number_format($d['montant'], 2, ',', ' ') ?> GNF</td>
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($d['date_don']) ?></td>
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($d['motif']) ?></td>
                            <td class="px-4 py-2 border-b"><?= isset($d['mode_paiement']) ? ($d['mode_paiement'] === 'orange_money' ? 'Orange Money' : 'Espèces') : 'Espèces' ?></td>
                            <td class="px-4 py-2 border-b flex gap-2">
                                <a href="modifier_don.php?id=<?= $d['id'] ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold px-3 py-1 rounded transition">Modifier</a>
                                <a href="liste_dons.php?delete=<?= $d['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce don ?');" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold px-3 py-1 rounded transition">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-6 text-gray-500">Aucun don trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-2">Total des dons par motif</h2>
            <ul class="list-disc pl-6">
                <?php foreach ($totaux_motif as $motif_clean => $total): ?>
                    <li>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars(motif_label_majoritaire($motif_labels[$motif_clean])) ?> :</span> <span class="font-bold text-green-700"><?= number_format($total, 2, ',', ' ') ?> GNF</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-green-400 hover:bg-green-500 text-white font-bold px-6 py-2 rounded shadow transition">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
