<?php
require_once '../../config/config.php';

// Suppression d'un paiement
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM contributions WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: liste_paiements.php?deleted=1');
    exit();
}

// Recherche par membre
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT c.*, m.nom, m.prenom FROM contributions c JOIN members m ON c.membre_id = m.id WHERE m.nom LIKE ? OR m.prenom LIKE ? ORDER BY c.id DESC');
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
    $paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query('SELECT c.*, m.nom, m.prenom FROM contributions c JOIN members m ON c.membre_id = m.id ORDER BY c.id DESC');
    $paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Calculs par membre pour le mois courant
$mois_courant = date('Y-m');
$par_membre = [];
foreach ($paiements as $p) {
    $mid = $p['membre_id'];
    if (!isset($par_membre[$mid])) {
        $par_membre[$mid] = [
            'nom' => $p['nom'],
            'prenom' => $p['prenom'],
            'hebdo_count' => 0,
            'mensuel_count' => 0,
            'total_mois' => 0,
            'total_global' => 0
        ];
    }
    // Total global
    $par_membre[$mid]['total_global'] += $p['montant'];
    // Pour le mois courant
    if (strpos($p['date_cotisation'], $mois_courant) === 0) {
        $par_membre[$mid]['total_mois'] += $p['montant'];
        if ($p['type'] === 'hebdomadaire') $par_membre[$mid]['hebdo_count']++;
        if ($p['type'] === 'mensuel') $par_membre[$mid]['mensuel_count']++;
    }
}
// Après le calcul, transformer les lots de 4 hebdo en 1 paiement mensuel supplémentaire
foreach ($par_membre as $mid => &$info) {
    if ($info['hebdo_count'] >= 4) {
        $info['mensuel_count'] += floor($info['hebdo_count'] / 4);
        $info['hebdo_count'] = $info['hebdo_count'] % 4;
    }
}
unset($info);
// Total général du mois courant
$total_mensuel = 0;
foreach ($paiements as $p) {
    if (strpos($p['date_cotisation'], $mois_courant) === 0) {
        $total_mensuel += $p['montant'];
    }
}
// Nombre de semaines dans le mois courant
$nb_semaines = 4; // fallback

// Ajout du filtre par dernier paiement
$filtre_dernier = isset($_GET['dernier']) && $_GET['dernier'] == '1';
if ($filtre_dernier) {
    // On ne garde que les membres dont le dernier paiement est dans le mois courant
    foreach ($par_membre as $mid => $info) {
        // Chercher la dernière date de paiement de ce membre
        $dates = array();
        foreach ($paiements as $p) {
            if ($p['membre_id'] == $mid) $dates[] = $p['date_cotisation'];
        }
        if (count($dates) > 0) {
            rsort($dates);
            $last = $dates[0];
            if (strpos($last, $mois_courant) !== 0) {
                unset($par_membre[$mid]);
            }
        } else {
            unset($par_membre[$mid]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des paiements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <h1 class="text-3xl font-extrabold text-yellow-700 mb-6 text-center">Liste des paiements</h1>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <form method="get" class="flex flex-1 items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom ou prénom..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 w-full md:w-64">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-4 py-2 rounded shadow transition">Rechercher</button>
            </form>
            <form method="get" class="flex items-center justify-end w-full md:w-auto">
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" name="dernier" value="1" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 rounded-full shadow transition focus:ring-2 focus:ring-blue-400 <?= $filtre_dernier ? 'ring-2 ring-blue-600' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Filtrer par dernier paiement ce mois
                </button>
            </form>
            <?php if ($search !== '' || $filtre_dernier): ?>
                <a href="liste_paiements.php" class="text-blue-600 hover:underline text-sm ml-2">Réinitialiser la recherche</a>
            <?php endif; ?>
        </div>
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">Paiement ajouté avec succès.</div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">Paiement supprimé avec succès.</div>
        <?php endif; ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-yellow-100">
                    <tr>
                        <th class="px-4 py-3 border-b text-left">Membre</th>
                        <th class="px-4 py-3 border-b text-left">Nb Paiements Hebdo (mois)</th>
                        <th class="px-4 py-3 border-b text-left">Nb Paiements Mensuel (mois)</th>
                        <th class="px-4 py-3 border-b text-left">Total payé ce mois</th>
                        <th class="px-4 py-3 border-b text-left">Total global</th>
                        <th class="px-4 py-3 border-b text-left">Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($par_membre) > 0): ?>
                    <?php foreach ($par_membre as $mid => $info): ?>
                        <?php
                        $statut = '';
                        $color = 'text-green-700';
                        if ($info['mensuel_count'] >= 1 || $info['hebdo_count'] >= $nb_semaines) {
                            $statut = 'À jour';
                        } else {
                            $statut = 'Retard';
                            $color = 'text-red-700 font-bold';
                        }
                        ?>
                        <tr class="hover:bg-yellow-50 transition">
                            <td class="px-4 py-2 border-b font-semibold text-gray-800"><?= htmlspecialchars($info['nom'] . ' ' . $info['prenom']) ?></td>
                            <td class="px-4 py-2 border-b text-center"><?= $info['hebdo_count'] ?> / <?= $nb_semaines ?></td>
                            <td class="px-4 py-2 border-b text-center"><?= $info['mensuel_count'] ?></td>
                            <td class="px-4 py-2 border-b font-bold text-blue-700"><?= number_format($info['total_mois'], 2, ',', ' ') ?> GNF</td>
                            <td class="px-4 py-2 border-b font-bold text-gray-700"><?= number_format($info['total_global'], 2, ',', ' ') ?> GNF</td>
                            <td class="px-4 py-2 border-b <?= $color ?>"><?= $statut ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-6 text-gray-500">Aucun paiement trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-2">Total des paiements du mois courant</h2>
            <div class="text-2xl font-extrabold text-green-700">
                <?= number_format($total_mensuel, 2, ',', ' ') ?> GNF
            </div>
        </div>
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-6 py-2 rounded shadow transition">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
