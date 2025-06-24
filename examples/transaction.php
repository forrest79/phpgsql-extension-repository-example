<?php declare(strict_types=1);

namespace App;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$userRepository = new Models\User($connection);

$transaction = $connection->transaction();

$transaction->execute(function () use ($connection, $userRepository, $transaction): void { // main transaction
	$userRepository->delete(1);

	$transaction->execute(function () use ($userRepository): void { // savepoint
		$userRepository->delete(2);
	});
});

var_dump($userRepository->table()->select(['id'])->orderBy('id')->fetchPairs(null, 'id'));
