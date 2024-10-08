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
        $data = array_map(function ($value) {
            return !empty($value) ? $value : 'N/A';
        }, $data);

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





