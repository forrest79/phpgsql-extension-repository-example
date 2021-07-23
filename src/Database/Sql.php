<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql\Db;

abstract class Sql
{

	public static function withAlias(string $column, ?string $alias): string
	{
		return ($alias === '') || ($alias === NULL)
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
		return new Db\Sql\Expression($value, $params);
	}

}
