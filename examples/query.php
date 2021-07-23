<?php declare(strict_types=1);

namespace App;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$userRepository = new Models\User($connection);

// exists()

echo '> exists()' . PHP_EOL . PHP_EOL;

var_dump($userRepository->table()->wherePrimary(1)->exists());
var_dump($userRepository->table()->wherePrimary(10)->exists());

// count()

echo PHP_EOL . '> count()' . PHP_EOL . PHP_EOL;

var_dump($userRepository->table()->where('length(nick) > ?', 5)->count());
var_dump(count($userRepository->table()->where('length(nick) > ?', 5)));

// orderByValues()

echo PHP_EOL . '> orderByValues()' . PHP_EOL . PHP_EOL;

$queryOrder = $userRepository->table()
	->select(['id', 'nick', 'active'])
	->orderByValues('id', [5, 4, 3, 2, 1]);

foreach ($queryOrder as $row) {
	assert($row instanceof Database\Row);
	var_dump($row->toArray());
}

// wrap() - put query into FROM to a new query and this query is returned

echo PHP_EOL . '> wrap()' . PHP_EOL . PHP_EOL;

$queryWrap = $userRepository->table('u')
	->select(['u.nick', 'rn' => 'row_number() OVER(PARTITION BY ud.department_id ORDER BY u.nick)'])
	->joins()->userDepartments('ud', 'u.id')
	->wrap('x')
	->select(['x.nick'])
	->where('x.rn', 1);

foreach ($queryWrap as $row) {
	assert($row instanceof Database\Row);
	var_dump($row->toArray());
}
