<?php

declare(strict_types=1);

$root = 'C:\xampp\htdocs\Opdracht 2' . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

function getTransactionFiles(string $directory): array
{
    return glob($directory . '*.csv');
}

$files = getTransactionFiles(FILES_PATH);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactiebestanden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Beschikbare transactiebestanden</h1>
    <?php if (!empty($files)): ?>
        <ul class="list-group">
            <?php foreach ($files as $file): ?>
                <li class="list-group-item">
                    <a href="views/transactions.php?file=<?= urlencode(basename($file)); ?>">
                        <?= basename($file); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Geen transactiebestanden gevonden in de directory.</p>
    <?php endif; ?>
</div>
</body>
</html>

