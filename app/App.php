<?php

declare(strict_types=1);
function getTransactions(string $filename): array
{
    $transactions = [];

    if (!file_exists($filename)) {
        return $transactions;
    }

    $file = fopen($filename, 'r');
    if (!$file) {
        throw new RuntimeException("Could not open the file: $filename");
    }

    while (($data = fgetcsv($file, 1000, ',')) !== false) {
        if (str_contains($data[0], 'datum')) {
            continue;
        }
        $transactions[] = $data;
    }

    fclose($file);
    return $transactions;
}

function formatDate($date): string
{
    $months = [
        '01' => 'januari', '02' => 'februari', '03' => 'maart', '04' => 'april',
        '05' => 'mei', '06' => 'juni', '07' => 'juli', '08' => 'augustus',
        '09' => 'september', '10' => 'oktober', '11' => 'november', '12' => 'december'
    ];

    $dateParts = explode('/', $date);

    // Check if the date is in the correct format
    if (count($dateParts) === 3) {
        $day = intval($dateParts[0]);
        $monthKey = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
        $year = $dateParts[2];

        if (isset($months[$monthKey])) {
            return $day . ' ' . $months[$monthKey] . ' ' . $year;
        } else {
            return 'Ongeldige maand';
        }
    }

    return 'Ongeldig datum';
}


function formatAmount(float $amount): string
{
    $color = $amount < 0 ? 'red' : 'green';
    return sprintf('<span style="color: %s;">€%.2f</span>', $color, $amount);
}

function calculateTotals(array $transactions): array
{
    $totaleInkomsten = 0;
    $totaleKosten = 0;

    foreach ($transactions as $transaction) {
        if (isset($transaction[3])) {
            $amount = (float)$transaction[3];
        } else {
            $amount = 0;
        }

        if ($amount > 0) {
            $totaleInkomsten += $amount;
        } else {
            $totaleKosten += abs($amount);
        }
    }

    $nettoTotal = $totaleInkomsten - $totaleKosten;
    return [$totaleInkomsten, $totaleKosten, $nettoTotal];
}


