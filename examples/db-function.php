<?php declare(strict_types=1);

namespace App;

use Forrest79\Database;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$dbFunction = new Database\DbFunction($connection);

var_dump($dbFunction->run('random')->fetchSingle());
