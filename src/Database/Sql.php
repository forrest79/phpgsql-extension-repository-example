<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql\Db;

abstract class Sql
{

	public static function withAlias(string $column, string|null $alias): string
	{
		return ($alias === '') || ($alias === null)
			? $column
			: ($alias . '.' . $column);
	}


	public static function literal(string $value): Db\Sql\Literal
	{
		return new Db\Sql\Literal($value);
	}


	public static function now(): Db\Sql\Literal
	{
		return self::literal('CURRENT_TIMESTAMP');
	}


	public static function parameters(string $value, mixed ...$params): Db\Sql\Expression
	{
		assert(array_is_list($params));
		return new Db\Sql\Expression($value, $params);
	}

}
