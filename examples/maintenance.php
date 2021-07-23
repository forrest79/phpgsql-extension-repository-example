<?php declare(strict_types=1);

namespace App;

use Forrest79;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$maintenance = new Forrest79\Database\Maintenance($connection);
$maintenance->vacuumAnalyze();
