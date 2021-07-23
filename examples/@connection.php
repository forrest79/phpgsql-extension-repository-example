<?php declare(strict_types=1);

namespace App;

$localConfigFile = __DIR__ . '/@config.local.php';

if (!file_exists($localConfigFile)) {
	echo sprintf('Create file %s with @todo.', $localConfigFile) . PHP_EOL;
	exit(1);
}

$config = require $localConfigFile;
assert(is_array($config));

$connection = new Database\Connection(implode(' ', array_map(static function(mixed $key, mixed $value): string {
	return $key . '=' . $value;
}, array_keys($config), $config)));

$connection
	->setDefaultRowFactory(new Database\RowFactory())
	->setStatementTimeout(2);

return $connection;
