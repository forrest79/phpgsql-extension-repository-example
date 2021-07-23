<?php declare(strict_types=1);

namespace App\Models;

final class User extends Repository
{

	public static function getTableName(): string
	{
		return 'users';
	}


	public static function getDefaultJoinColumn(): string
	{
		return 'id';
	}

}
