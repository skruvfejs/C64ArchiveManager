<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Session;

$session = new Session();

$count = (int)$session->get('count', 0);
$count++;

$session->set('count', $count);

echo "<h1>Session Test</h1>";
echo "<p>Counter: {$count}</p>";
