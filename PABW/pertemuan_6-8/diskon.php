<?php
function hitungDiskon($totalBelanja) {
    $diskon = 0;

    if ($totalBelanja >= 100000) {
        $diskon = 0.10 * $totalBelanja;
    } elseif ($totalBelanja >= 50000) {
        $diskon = 0.05 * $totalBelanja;
    }
    return $diskon;
}

function rupiah($angka) {
    return "Rp. " . number_format($angka, 0, ',', '.');
}

$hasil = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["total_belanja"] ?? "";

    if (!is_numeric($input) || $input < 0) {
        $error = "Masukkan total belanja berupa angka yang valid.";
    } else {
        $totalBelanja = (float) $input;
        $diskon       = hitungDiskon($totalBelanja);
        $persen       = $totalBelanja >= 100000 ? 10 : ($totalBelanja >= 50000 ? 5 : 0);

        $hasil = [
            "total"       => $totalBelanja,
            "diskon"      => $diskon,
            "persen"      => $persen,
            "total_bayar" => $totalBelanja - $diskon,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Diskon Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6 sm:p-8">

        <h1 class="text-2xl font-bold text-slate-800">Kalkulator Diskon</h1>
        <p class="text-sm text-slate-500 mt-1">Masukkan total belanja untuk melihat diskon dan total bayar.</p>

        <form method="post" class="mt-6 space-y-4">
            <div>
                <label for="total_belanja" class="block text-sm font-medium text-slate-700 mb-1">
                    Total belanja (Rp)
                </label>
                <input
                    type="number"
                    id="total_belanja"
                    name="total_belanja"
                    min="0"
                    required
                    placeholder="contoh: 120000"
                    value="<?= htmlspecialchars($_POST['total_belanja'] ?? '') ?>"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 py-2.5 font-semibold text-white
                       hover:bg-blue-700 transition-colors">
                Hitung
            </button>
        </form>

        <?php if ($error): ?>
            <div class="mt-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($hasil): ?>
            <div class="mt-6 rounded-xl border border-slate-200 divide-y divide-slate-200">
                <div class="flex justify-between px-4 py-3 text-sm">
                    <span class="text-slate-500">Total belanja</span>
                    <span class="font-medium text-slate-800"><?= rupiah($hasil["total"]) ?></span>
                </div>
                <div class="flex justify-between px-4 py-3 text-sm">
                    <span class="text-slate-500">
                        Diskon
                        <?php if ($hasil["persen"] > 0): ?>
                            <span class="ml-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                <?= $hasil["persen"] ?>%
                            </span>
                        <?php endif; ?>
                    </span>
                    <span class="font-medium text-green-600">- <?= rupiah($hasil["diskon"]) ?></span>
                </div>
                <div class="flex justify-between px-4 py-3 bg-slate-50 rounded-b-xl">
                    <span class="font-semibold text-slate-800">Total bayar</span>
                    <span class="text-lg font-bold text-blue-600"><?= rupiah($hasil["total_bayar"]) ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
