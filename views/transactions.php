<?php

declare(strict_types=1);

require 'C:\xampp\htdocs\Opdracht 2/app/App.php';
$root = 'C:\xampp\htdocs\Opdracht 2' . DIRECTORY_SEPARATOR;

define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);

if (empty($_GET['file'])) {
    echo "Geen bestand geselecteerd.";
    exit;
}

$filename = FILES_PATH . $_GET['file'];

$transactions = getTransactions($filename);

function cleanAndStructureData(array $data): array
{
    $structuredData = [];

    foreach ($data as $entry) {
        $rawEntry = $entry[0];

        $parts = preg_split('/\s+/', trim($rawEntry));

        $date = $parts[0];
        $transactionID = isset($parts[1]) ? $parts[1] : 'N/A';
        $description = isset($parts[2]) ? $parts[2] : 'N/A';
        $amountValue = end($parts);

        $structuredData[] = [
            'Datum' => $date,
            'Check #' => $transactionID,
            'Beschrijving' => $description,
            'Bedrag' => $amountValue
        ];
    }
    return $structuredData;
}

$structuredTransactions = cleanAndStructureData($transactions);

[$totaleInkomsten, $totaleKosten, $nettoTotal] = calculateTotals($structuredTransactions);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transacties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th, table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }

        tfoot tr th, tfoot tr td {
            font-size: 20px;
        }

        tfoot tr th {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Transacties van <?= htmlspecialchars($_GET['file']); ?></h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Datum</th>
            <th>Check #</th>
            <th>Beschrijving</th>
            <th>Bedrag</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($structuredTransactions as $transaction): ?>
            <tr>
                <td><?= formatDate($transaction['Datum']); ?></td>
                <td><?= htmlspecialchars($transaction['Check #']); ?></td>
                <td><?= htmlspecialchars($transaction['Beschrijving']); ?></td>
                <td><?= formatAmount((float)$transaction['Bedrag']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">Totale Inkomsten:</th>
            <td style="color: green;"><?= formatAmount($totaleInkomsten); ?></td>
        </tr>
        <tr>
            <th colspan="3">Totale Uitgaven:</th>
            <td style="color: red;"><?= formatAmount(-$totaleKosten); ?></td>
        </tr>
        <tr>
            <th colspan="3">Netto Totaal:</th>
            <td style="color: <?= $nettoTotal >= 0 ? 'green' : 'red'; ?>;">
                <?= formatAmount($nettoTotal); ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>