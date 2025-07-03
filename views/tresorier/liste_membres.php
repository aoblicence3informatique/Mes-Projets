<?php
require_once '../../config/config.php';

// Suppression d'un membre
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM members WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: liste_membres.php?deleted=1');
    exit();
}

// Recherche par contact
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT id, nom, prenom, contact, sexe, statut FROM members WHERE contact LIKE ? ORDER BY id DESC');
    $stmt->execute(['%' . $search . '%']);
    $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query('SELECT id, nom, prenom, contact, sexe, statut FROM members ORDER BY id DESC');
    $membres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des membres</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-yellow-700 mb-6 text-center">Liste des membres</h1>
        
        <!-- Champ de recherche -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <form method="get" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par contact..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 w-full md:w-64">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-4 py-2 rounded shadow transition">Rechercher</button>
            </form>
            <?php if ($search !== ''): ?>
                <a href="liste_membres.php" class="text-blue-600 hover:underline text-sm">Réinitialiser la recherche</a>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-yellow-100">
                    <tr>
                        <th class="px-4 py-3 border-b text-left">N°</th>
                        <th class="px-4 py-3 border-b text-left">Nom</th>
                        <th class="px-4 py-3 border-b text-left">Prénom</th>
                        <th class="px-4 py-3 border-b text-left">Contact</th>
                        <th class="px-4 py-3 border-b text-left">Sexe</th>
                        <th class="px-4 py-3 border-b text-left">Statut</th>
                        <th class="px-4 py-3 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($membres) > 0): ?>
                    <?php foreach ($membres as $membre): ?>
                        <tr class="hover:bg-yellow-50 transition">
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($membre['id']) ?></td>
                            <td class="px-4 py-2 border-b font-semibold text-gray-800"><?= htmlspecialchars($membre['nom']) ?></td>
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($membre['prenom']) ?></td>
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars($membre['contact']) ?></td>
                            <td class="px-4 py-2 border-b"><?= htmlspecialchars(ucfirst($membre['sexe'])) ?></td>
                            <td class="px-4 py-2 border-b">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $membre['statut']==='actif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= htmlspecialchars(ucfirst($membre['statut'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b flex gap-2">
                                <a href="modifier_membre.php?id=<?= $membre['id'] ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold px-3 py-1 rounded transition">Modifier</a>
                                <a href="liste_membres.php?delete=<?= $membre['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce membre ?');" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold px-3 py-1 rounded transition">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-6 text-gray-500">Aucun membre trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-2 rounded shadow transition">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
