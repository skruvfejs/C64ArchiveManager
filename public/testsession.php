<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Session;

$session = new Session();

$count = $session->get('count', 0);
$count++;

$session->set('count', $count);

echo "Session fungerar.<br>";
echo "Antal besök: " . $count;

