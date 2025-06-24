<?php declare(strict_types=1);

namespace App;

require __DIR__ . '/../vendor/autoload.php';

$connection = require __DIR__ . '/@connection.php';
assert($connection instanceof Database\Connection);

$userRepository = new Models\User($connection);

// Prepared joins via objects

$query = $userRepository->table('u')
	->select(['u.nick', 'department_name' => 'd.name'])
	->joins()->userDepartmentsByUserId('ud', 'u.id')
	->joins()->departments('d', 'ud.department_id')
	->where('d.active', true);

var_dump($query->fetchPairs('nick', 'department_name'));

// Manual repository join

$query = $userRepository->table('u')
	->select(['u.nick', 'department_name' => 'd.name'])
	->joinRepository(Models\UserDepartment::class, 'ud', 'u.id')
	->joinRepository(Models\Department::class, 'd', 'ud.department_id')
	->where('d.active', true);

var_dump($query->fetchPairs('nick', 'department_name'));
