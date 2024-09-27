<?php

declare(strict_types=1);

require_once 'C:\xampp\htdocs\Opdracht 2/app/App.php';
$root = 'C:\xampp\htdocs\Opdracht 2' . DIRECTORY_SEPARATOR;

define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);

if (empty($_GET['file'])) {
    echo "Geen bestand geselecteerd.";
    exit;
}

$filename = FILES_PATH . $_GET['file'];

$transactions = getTransactions($filename);

$transactions = getTransactions($filename);

[$totalInkomsten, $totalKosten, $netTotal] = calculateTotals($transactions);

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
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?= !empty($transaction[0]) ? formatDate($transaction[0]) : 'N/A'; ?></td>
                <td><?= !empty($transaction[1]) ? htmlspecialchars($transaction[1]) : 'N/A'; ?></td>
                <td><?= !empty($transaction[2]) ? htmlspecialchars($transaction[2]) : 'Geen beschrijving'; ?></td>
                <td><?= !empty($transaction[3]) ? formatAmount((float)$transaction[3]) : '0'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">Totale Inkomsten:</th>
            <td><?= formatAmount($totalInkomsten); ?></td>
        </tr>
        <tr>
            <th colspan="3">Totale Uitgaven:</th>
            <td><?= formatAmount(-$totalKosten); ?></td>
        </tr>
        <tr>
            <th colspan="3">Netto totaal:</th>
            <td><?= formatAmount($netTotal); ?></td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
