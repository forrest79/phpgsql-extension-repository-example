<?php declare(strict_types=1);

namespace App\Models;

final class UserDepartment extends Repository
{

	public static function getTableName(): string
	{
		return 'user_departments';
	}

}
