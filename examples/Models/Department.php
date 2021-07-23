<?php declare(strict_types=1);

namespace App\Models;

final class Department extends Repository
{

	public static function getTableName(): string
	{
		return 'departments';
	}


	public static function getDefaultJoinColumn(): string
	{
		return 'id';
	}

}
