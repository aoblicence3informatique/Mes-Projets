<?php
require_once '../../config/config.php';

$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
$periode = isset($_GET['periode']) ? $_GET['periode'] : 'annee'; // 'mois', 'trimestre', 'annee'
$mois_fr = [1=>'Janv',2=>'Fév',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juil',8=>'Août',9=>'Sept',10=>'Oct',11=>'Nov',12=>'Déc'];
$trimestres = [1=>'Janv-Mars',2=>'Avr-Juin',3=>'Juil-Sept',4=>'Oct-Déc'];
$stats_cot = [];
$stats_don = [];
$labels = [];
$hasData = false;

if ($periode === 'mois') {
    for ($m=1; $m<=12; $m++) {
        $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE MONTH(date_cotisation)=? AND YEAR(date_cotisation)=?');
        $stmt->execute([$m, $annee]);
        $cot = (float)$stmt->fetchColumn();
        $stats_cot[] = $cot;
        $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE MONTH(date_don)=? AND YEAR(date_don)=?');
        $stmt->execute([$m, $annee]);
        $don = (float)$stmt->fetchColumn();
        $stats_don[] = $don;
        $labels[] = $mois_fr[$m];
        if ($cot > 0 || $don > 0) $hasData = true;
    }
    $titre = "Cotisations et Dons par mois ($annee)";
} elseif ($periode === 'trimestre') {
    for ($t=1; $t<=4; $t++) {
        $mois_deb = 1 + ($t-1)*3;
        $mois_fin = $t*3;
        $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE MONTH(date_cotisation) BETWEEN ? AND ? AND YEAR(date_cotisation)=?');
        $stmt->execute([$mois_deb, $mois_fin, $annee]);
        $cot = (float)$stmt->fetchColumn();
        $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE MONTH(date_don) BETWEEN ? AND ? AND YEAR(date_don)=?');
        $stmt->execute([$mois_deb, $mois_fin, $annee]);
        $don = (float)$stmt->fetchColumn();
        $stats_cot[] = $cot;
        $stats_don[] = $don;
        $labels[] = $trimestres[$t];
        if ($cot > 0 || $don > 0) $hasData = true;
    }
    $titre = "Cotisations et Dons par trimestre ($annee)";
} else { // année
    $stmt = $pdo->prepare('SELECT SUM(montant) FROM contributions WHERE YEAR(date_cotisation)=?');
    $stmt->execute([$annee]);
    $cot = (float)$stmt->fetchColumn();
    $stmt = $pdo->prepare('SELECT SUM(montant) FROM donations WHERE YEAR(date_don)=?');
    $stmt->execute([$annee]);
    $don = (float)$stmt->fetchColumn();
    $stats_cot[] = $cot;
    $stats_don[] = $don;
    $labels[] = $annee;
    if ($cot > 0 || $don > 0) $hasData = true;
    $titre = "Cotisations et Dons sur l'année ($annee)";
}

if ($hasData) {
    $total_cotisations = array_sum($stats_cot);
    $total_dons = array_sum($stats_don);
    $nb_membres = $pdo->query('SELECT COUNT(*) FROM members')->fetchColumn();
    $nb_donateurs = $pdo->prepare('SELECT COUNT(DISTINCT donateur) FROM donations WHERE YEAR(date_don)=?');
    $nb_donateurs->execute([$annee]);
    $nb_donateurs = $nb_donateurs->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques AJVDNK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gradient-to-br from-pink-50 via-white to-green-50 min-h-screen flex flex-col items-center py-12">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl p-10 mt-10 mb-10">
        <a href="index.php" class="inline-block mb-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-6 py-2 rounded shadow transition">&larr; Retour à l'accueil</a>
        <h1 class="text-3xl font-extrabold text-pink-700 mb-6 text-center tracking-wide">Statistiques AJVDNK</h1>
        <form method="get" class="flex flex-wrap gap-4 justify-center mb-8">
            <label class="font-bold">Année :
                <select name="annee" class="border rounded px-2 py-1 focus:ring-2 focus:ring-pink-300" onchange="this.form.submit()">
                    <?php for($a=date('Y')-5;$a<=date('Y');$a++): ?>
                        <option value="<?= $a ?>" <?= ($annee==$a)?'selected':'' ?>><?= $a ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <label class="font-bold">Période :
                <select name="periode" class="border rounded px-2 py-1 focus:ring-2 focus:ring-pink-300" onchange="this.form.submit()">
                    <option value="mois" <?= $periode=='mois'?'selected':'' ?>>Par mois</option>
                    <option value="trimestre" <?= $periode=='trimestre'?'selected':'' ?>>Par trimestre</option>
                    <option value="annee" <?= $periode=='annee'?'selected':'' ?>>Par année</option>
                </select>
            </label>
        </form>
        <div class="flex justify-end mb-4 gap-4">
            <button onclick="window.print()" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded shadow transition print:hidden">
                🖨️ Exporter / Imprimer le rapport
            </button>
            <button onclick="exportPDF()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow transition print:hidden">
                📄 Exporter en PDF
            </button>
        </div>
        <?php if ($hasData): ?>
        <style>
.stat-box {
    background: rgba(255,255,255,0.7);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
    backdrop-filter: blur(6px);
    border-radius: 1.5rem;
    border: 1.5px solid rgba(236, 72, 153, 0.15);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
}
.stat-box:hover {
    transform: translateY(-6px) scale(1.04);
    box-shadow: 0 16px 40px 0 rgba(236, 72, 153, 0.18);
    z-index: 2;
}
.stat-icon {
    position: absolute;
    top: -28px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #ec4899 60%, #fbbf24 100%);
    border-radius: 50%;
    padding: 0.7rem;
    box-shadow: 0 2px 8px 0 rgba(236, 72, 153, 0.10);
    color: white;
    font-size: 2rem;
    border: 3px solid #fff;
}
@media print {
    body { background: #fff !important; }
    .print\:hidden, .print\:hidden * { display: none !important; }
    .shadow-2xl, .shadow-lg, .shadow, .bg-gradient-to-br, .bg-pink-50, .bg-green-50, .bg-white { box-shadow: none !important; background: #fff !important; }
    .stat-box, .stat-icon { box-shadow: none !important; background: #fff !important; }
    a, button { display: none !important; }
    .max-w-4xl, .max-w-lg { max-width: 100% !important; }
}
</style>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
    <div class="stat-box p-8 pt-12 text-center">
        <div class="stat-icon"><svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 16v-4m8-4h-4m-8 0H4"></path></svg></div>
        <div class="text-3xl font-extrabold text-pink-700 mb-2 drop-shadow"><?= number_format($total_cotisations,0,',',' ') ?> GNF</div>
        <div class="text-gray-700 font-semibold tracking-wide">Total cotisations</div>
    </div>
    <div class="stat-box p-8 pt-12 text-center">
        <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e 60%,#fbbf24 100%)"><svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 16v-4m8-4h-4m-8 0H4"></path></svg></div>
        <div class="text-3xl font-extrabold text-green-700 mb-2 drop-shadow"><?= number_format($total_dons,0,',',' ') ?> GNF</div>
        <div class="text-gray-700 font-semibold tracking-wide">Total dons</div>
    </div>
    <div class="stat-box p-8 pt-12 text-center">
        <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6 60%,#fbbf24 100%)"><svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4v2a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"></path></svg></div>
        <div class="text-3xl font-extrabold text-blue-700 mb-2 drop-shadow"><?= $nb_membres ?></div>
        <div class="text-gray-700 font-semibold tracking-wide">Nombre de membres</div>
    </div>
    <div class="stat-box p-8 pt-12 text-center">
        <div class="stat-icon" style="background:linear-gradient(135deg,#fbbf24 60%,#ec4899 100%)"><svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg></div>
        <div class="text-3xl font-extrabold text-yellow-700 mb-2 drop-shadow"><?= $nb_donateurs ?></div>
        <div class="text-gray-700 font-semibold tracking-wide">Nombre de donateurs</div>
    </div>
</div>
<div class="mb-10 bg-gradient-to-br from-pink-50 via-white to-green-50 rounded-2xl p-6 shadow-lg">
    <canvas id="chartCotDon" height="100"></canvas>
</div>
<?php else: ?>
        <div class="flex flex-col items-center justify-center py-16">
            <svg class="w-20 h-20 text-gray-300 mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xl text-gray-400 font-semibold">Aucune donnée disponible pour l'année sélectionnée.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($hasData): ?>
    <script>
    const ctx = document.getElementById('chartCotDon').getContext('2d');
    const gradientCot = ctx.createLinearGradient(0, 0, 0, 300);
    gradientCot.addColorStop(0, 'rgba(236,72,153,0.9)');
    gradientCot.addColorStop(1, 'rgba(236,72,153,0.2)');
    const gradientDon = ctx.createLinearGradient(0, 0, 0, 300);
    gradientDon.addColorStop(0, 'rgba(34,197,94,0.9)');
    gradientDon.addColorStop(1, 'rgba(34,197,94,0.2)');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Cotisations',
                    data: <?= json_encode($stats_cot) ?>,
                    backgroundColor: gradientCot,
                    borderRadius: 12,
                    borderSkipped: false,
                    borderWidth: 2,
                    borderColor: 'rgba(236,72,153,0.7)',
                    hoverBackgroundColor: 'rgba(236,72,153,1)'
                },
                {
                    label: 'Dons',
                    data: <?= json_encode($stats_don) ?>,
                    backgroundColor: gradientDon,
                    borderRadius: 12,
                    borderSkipped: false,
                    borderWidth: 2,
                    borderColor: 'rgba(34,197,94,0.7)',
                    hoverBackgroundColor: 'rgba(34,197,94,1)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#222',
                        font: { weight: 'bold', size: 16 },
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: <?= json_encode($titre) ?>,
                    color: '#ec4899',
                    font: { size: 22, weight: 'bold' },
                    padding: { top: 10, bottom: 30 }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#ec4899',
                    bodyColor: '#222',
                    borderColor: '#ec4899',
                    borderWidth: 1,
                    padding: 12,
                    caretSize: 8,
                    cornerRadius: 8
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#888', font: { weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(236,72,153,0.08)' },
                    ticks: { color: '#888', font: { weight: 'bold' } }
                }
            }
        }
    });
    </script>
    <?php endif; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
    // Exporter le rapport/statistiques en PDF sans les boutons et avec fond blanc/texte noir
    function exportPDF() {
        // Cacher tous les boutons (y compris retour accueil)
        var boutons = document.querySelectorAll('button, a, .print\\:hidden, .print\\:hidden *');
        boutons.forEach(function(btn) { btn.classList.add('hidden'); });
        // Forcer fond blanc et texte noir
        document.body.classList.add('pdf-export');
        var rapport = document.querySelector('.w-full.max-w-4xl.bg-white.rounded-2xl.shadow-2xl.p-10.mt-10.mb-10');
        html2canvas(rapport, {backgroundColor: '#fff'}).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');
            var pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
            var pageWidth = pdf.internal.pageSize.getWidth();
            var imgWidth = pageWidth - 20;
            var imgHeight = canvas.height * imgWidth / canvas.width;
            var y = 10;
            pdf.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight);
            pdf.save('statistiques_ajvdnk.pdf');
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
