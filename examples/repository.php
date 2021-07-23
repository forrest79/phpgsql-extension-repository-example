<?php declare(strict_types=1);

namespace App;

use Forrest79\Database\Sql;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$userRepository = new Models\User($connection);

// insert

echo '> insert:' . PHP_EOL . PHP_EOL;

var_dump($userRepository->insert([
	'nick' => 'Test1',
	'inserted_datetime' => Sql::now(),
	'active' => TRUE,
])->getAffectedRows());

$newId = $userRepository->insertReturning([
	'nick' => 'Test2',
	'inserted_datetime' => Sql::now(),
	'active' => TRUE,
	'age' => 29,
], ['id'])->id;

var_dump($newId);

// get()

echo PHP_EOL . '> get:' . PHP_EOL . PHP_EOL;

$newUser = $userRepository->get($newId, ['nick']);
assert($newUser instanceof Database\Row);

var_dump($newUser->nick);

// update

echo PHP_EOL . '> update:' . PHP_EOL . PHP_EOL;

var_dump($userRepository->updateReturning($newId, [
	'nick' => 'Test3',
	'active' => Sql::parameters('CASE WHEN age > ? THEN TRUE ELSE FALSE END', 30),
], ['active'])->active);

// delete

echo PHP_EOL . '> delete:' . PHP_EOL . PHP_EOL;

var_dump($userRepository->delete($newId)->getAffectedRows());

// alias

echo PHP_EOL . '> alias:' . PHP_EOL . PHP_EOL;

$alias = 'x'; // this could be function argument

$nick = $userRepository->table($alias)
	->select([Sql::withAlias('nick', $alias)])
	->where(Sql::withAlias('id', $alias), 5)
	->fetchSingle();

var_dump($nick);
