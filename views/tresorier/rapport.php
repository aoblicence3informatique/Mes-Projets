<?php
require_once '../../config/config.php';

// Trouver la date la plus récente (paiement ou don)
$date_max = null;
$stmt = $pdo->query("SELECT MAX(date_cotisation) FROM contributions");
$date_cotisation_max = $stmt->fetchColumn();
$stmt = $pdo->query("SELECT MAX(date_don) FROM donations");
$date_don_max = $stmt->fetchColumn();
if ($date_cotisation_max && $date_don_max) {
    $date_max = (strtotime($date_cotisation_max) > strtotime($date_don_max)) ? $date_cotisation_max : $date_don_max;
} elseif ($date_cotisation_max) {
    $date_max = $date_cotisation_max;
} elseif ($date_don_max) {
    $date_max = $date_don_max;
}

// Si aucune donnée, prendre la date du jour
if (!$date_max) {
    $date_max = date('Y-m-d');
}

// Calculer le mois et l'année de fin
$mois_fin = (int)date('m', strtotime($date_max));
$annee_fin = (int)date('Y', strtotime($date_max));
// Calculer le mois et l'année de début (3 mois avant)
$mois_debut = $mois_fin - 2;
$annee_debut = $annee_fin;
if ($mois_debut <= 0) {
    $mois_debut += 12;
    $annee_debut--;
}

// Générer la liste des mois concernés (en tenant compte du changement d'année)
$mois_rapport = [];
$annees_rapport = [];
for ($i = 0; $i < 3; $i++) {
    $mois = $mois_debut + $i;
    $annee = $annee_debut;
    if ($mois > 12) {
        $mois -= 12;
        $annee++;
    }
    $mois_rapport[] = $mois;
    $annees_rapport[] = $annee;
}

// Tableau des mois en français
$mois_fr = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
    7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];

// Calcul des totaux cotisations (table contributions)
$total_cotisations = 0;
foreach ($mois_rapport as $idx => $m) {
    $a = $annees_rapport[$idx];
    $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE MONTH(date_cotisation) = ? AND YEAR(date_cotisation) = ?');
    $stmt->execute([$m, $a]);
    $total_cotisations += (float)$stmt->fetchColumn();
}
// Calcul des totaux dons
$total_dons = 0;
foreach ($mois_rapport as $idx => $m) {
    $a = $annees_rapport[$idx];
    $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE MONTH(date_don) = ? AND YEAR(date_don) = ?');
    $stmt->execute([$m, $a]);
    $total_dons += (float)$stmt->fetchColumn();
}
$total_general = $total_cotisations + $total_dons;

// Affichage des mois du rapport
$mois_labels = array_map(function($m, $a) use ($mois_fr) {
    return $mois_fr[$m] . ' ' . $a;
}, $mois_rapport, $annees_rapport);
$periode = implode(' - ', [$mois_labels[0], $mois_labels[2]]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport financier (3 derniers mois)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <a href="index.php" class="inline-block mb-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-6 py-2 rounded shadow transition">&larr; Retour à l'accueil</a>
        <h1 class="text-2xl font-extrabold text-purple-700 mb-6 text-center">Rapport financier (3 derniers mois)</h1>
        <div class="text-center mb-6 text-lg font-semibold text-gray-700">Période : <?= htmlspecialchars($periode) ?></div>
        <h2 class="text-xl font-bold text-blue-700 mt-8 mb-2">Cotisations</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-blue-100">
                    <th class="py-2 px-4 border">Date</th>
                    <th class="py-2 px-4 border">Montant total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dates_cotisations = [];
                foreach ($mois_rapport as $idx => $m) {
                    $a = $annees_rapport[$idx];
                    $stmt = $pdo->prepare('SELECT date_cotisation, SUM(montant) as total FROM contributions WHERE MONTH(date_cotisation) = ? AND YEAR(date_cotisation) = ? GROUP BY date_cotisation ORDER BY date_cotisation ASC');
                    $stmt->execute([$m, $a]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $dates_cotisations[$row['date_cotisation']] = (isset($dates_cotisations[$row['date_cotisation']]) ? $dates_cotisations[$row['date_cotisation']] : 0) + $row['total'];
                    }
                }
                foreach ($dates_cotisations as $date => $total) {
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . htmlspecialchars($date) . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <h2 class="text-xl font-bold text-green-700 mt-8 mb-2">Dons</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-green-100">
                    <th class="py-2 px-4 border">Date</th>
                    <th class="py-2 px-4 border">Montant total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dates_dons = [];
                foreach ($mois_rapport as $idx => $m) {
                    $a = $annees_rapport[$idx];
                    $stmt = $pdo->prepare('SELECT date_don, SUM(montant) as total FROM donations WHERE MONTH(date_don) = ? AND YEAR(date_don) = ? GROUP BY date_don ORDER BY date_don ASC');
                    $stmt->execute([$m, $a]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $dates_dons[$row['date_don']] = (isset($dates_dons[$row['date_don']]) ? $dates_dons[$row['date_don']] : 0) + $row['total'];
                    }
                }
                foreach ($dates_dons as $date => $total) {
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . htmlspecialchars($date) . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <h2 class="text-xl font-bold text-purple-700 mt-8 mb-2">Synthèse mensuelle du trimestre</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-purple-100">
                    <th class="py-2 px-4 border">Mois</th>
                    <th class="py-2 px-4 border">Total Cotisations (GNF)</th>
                    <th class="py-2 px-4 border">Total Dons (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < 3; $i++) {
                    $m = $mois_rapport[$i];
                    $a = $annees_rapport[$i];
                    // Total cotisations pour ce mois
                    $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE MONTH(date_cotisation) = ? AND YEAR(date_cotisation) = ?');
                    $stmt->execute([$m, $a]);
                    $total_cot_mois = (float)$stmt->fetchColumn();
                    // Total dons pour ce mois
                    $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE MONTH(date_don) = ? AND YEAR(date_don) = ?');
                    $stmt->execute([$m, $a]);
                    $total_don_mois = (float)$stmt->fetchColumn();
                    // Affichage
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . $mois_fr[$m] . ' ' . $a . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total_cot_mois, 0, ',', ' ') . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total_don_mois, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <table class="w-full text-center border mt-6">
            <thead>
                <tr class="bg-purple-100">
                    <th class="py-2 px-4 border">Type</th>
                    <th class="py-2 px-4 border">Total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border">Cotisations</td>
                    <td class="py-2 px-4 border font-bold text-blue-700"><?= number_format($total_cotisations, 0, ',', ' ') ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border">Dons</td>
                    <td class="py-2 px-4 border font-bold text-green-700"><?= number_format($total_dons, 0, ',', ' ') ?></td>
                </tr>
                <tr class="bg-purple-50 font-extrabold">
                    <td class="py-2 px-4 border">Total général</td>
                    <td class="py-2 px-4 border text-purple-800"><?= number_format($total_general, 0, ',', ' ') ?></td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-end gap-4 mb-4">
            <button onclick="window.print()" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded shadow transition print:hidden">
                🖨️ Imprimer le rapport
            </button>
            <button onclick="exportTableToExcel('rapport-financier', 'rapport_ajvdnk')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded shadow transition print:hidden">
                ⬇️ Exporter vers Excel
            </button>
            <button onclick="exportPDF()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow transition print:hidden">
                📄 Exporter en PDF
            </button>
        </div>
    </div>
    <div id="rapport-financier" class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10 hidden">
        <h1 class="text-2xl font-extrabold text-purple-700 mb-6 text-center">Rapport financier (3 derniers mois)</h1>
        <div class="text-center mb-6 text-lg font-semibold text-gray-700">Période : <?= htmlspecialchars($periode) ?></div>
        <h2 class="text-xl font-bold text-blue-700 mt-8 mb-2">Cotisations</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-blue-100">
                    <th class="py-2 px-4 border">Date</th>
                    <th class="py-2 px-4 border">Montant total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dates_cotisations as $date => $total) {
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . htmlspecialchars($date) . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <h2 class="text-xl font-bold text-green-700 mt-8 mb-2">Dons</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-green-100">
                    <th class="py-2 px-4 border">Date</th>
                    <th class="py-2 px-4 border">Montant total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dates_dons as $date => $total) {
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . htmlspecialchars($date) . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <h2 class="text-xl font-bold text-purple-700 mt-8 mb-2">Synthèse mensuelle du trimestre</h2>
        <table class="w-full text-center border mb-8">
            <thead>
                <tr class="bg-purple-100">
                    <th class="py-2 px-4 border">Mois</th>
                    <th class="py-2 px-4 border">Total Cotisations (GNF)</th>
                    <th class="py-2 px-4 border">Total Dons (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < 3; $i++) {
                    $m = $mois_rapport[$i];
                    $a = $annees_rapport[$i];
                    // Total cotisations pour ce mois
                    $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE MONTH(date_cotisation) = ? AND YEAR(date_cotisation) = ?');
                    $stmt->execute([$m, $a]);
                    $total_cot_mois = (float)$stmt->fetchColumn();
                    // Total dons pour ce mois
                    $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE MONTH(date_don) = ? AND YEAR(date_don) = ?');
                    $stmt->execute([$m, $a]);
                    $total_don_mois = (float)$stmt->fetchColumn();
                    // Affichage
                    echo '<tr>';
                    echo '<td class="py-2 px-4 border">' . $mois_fr[$m] . ' ' . $a . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total_cot_mois, 0, ',', ' ') . '</td>';
                    echo '<td class="py-2 px-4 border">' . number_format($total_don_mois, 0, ',', ' ') . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <table class="w-full text-center border mt-6">
            <thead>
                <tr class="bg-purple-100">
                    <th class="py-2 px-4 border">Type</th>
                    <th class="py-2 px-4 border">Total (GNF)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border">Cotisations</td>
                    <td class="py-2 px-4 border font-bold text-blue-700"><?= number_format($total_cotisations, 0, ',', ' ') ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border">Dons</td>
                    <td class="py-2 px-4 border font-bold text-green-700"><?= number_format($total_dons, 0, ',', ' ') ?></td>
                </tr>
                <tr class="bg-purple-50 font-extrabold">
                    <td class="py-2 px-4 border">Total général</td>
                    <td class="py-2 px-4 border text-purple-800"><?= number_format($total_general, 0, ',', ' ') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
    // Exporter toutes les tables du rapport en un seul fichier Excel
    function exportTableToExcel(divId, filename = '') {
        var dataType = 'application/vnd.ms-excel';
        var tableDiv = document.getElementById(divId);
        var tableHTML = tableDiv.outerHTML.replace(/ /g, '%20');
        filename = filename ? filename + '.xls' : 'rapport.xls';
        var downloadLink = document.createElement('a');
        document.body.appendChild(downloadLink);
        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
        document.body.removeChild(downloadLink);
    }

    // Exporter le rapport en PDF sans les boutons et avec fond blanc/texte noir
    function exportPDF() {
        // Cacher tous les boutons (y compris retour accueil)
        var boutons = document.querySelectorAll('button, a, .print\\:hidden, .print\\:hidden *');
        boutons.forEach(function(btn) { btn.classList.add('hidden'); });
        // Forcer fond blanc et texte noir
        document.body.classList.add('pdf-export');
        var rapport = document.querySelector('.w-full.max-w-3xl.bg-white.rounded-2xl.shadow-2xl.p-10.mt-10.mb-10');
        html2canvas(rapport, {backgroundColor: '#fff'}).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');
            var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
            var pageWidth = pdf.internal.pageSize.getWidth();
            var imgWidth = pageWidth - 20;
            var imgHeight = canvas.height * imgWidth / canvas.width;
            var y = 10;
            pdf.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight);
            pdf.save('rapport_ajvdnk.pdf');
            // Réafficher les boutons et retirer le style
            boutons.forEach(function(btn) { btn.classList.remove('hidden'); });
            document.body.classList.remove('pdf-export');
        });
    }
    // Style PDF export (fond blanc, texte noir, pas d'ombre)
    var style = document.createElement('style');
    style.innerHTML = `
    .pdf-export, .pdf-export * {
        background: #fff !important;
        color: #111 !important;
        box-shadow: none !important;
    }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>
