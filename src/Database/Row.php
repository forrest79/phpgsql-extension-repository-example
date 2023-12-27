<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql\Db;

class Row extends Db\Row
{
	/** @var array<string, Value> */
	private array $valueObjects = [];


	public function value(string $column): Value
	{
		if (!isset($this->valueObjects[$column])) {
			$this->valueObjects[$column] = new Value($this->__get($column));
		}

		return $this->valueObjects[$column];
	}


	/**
	 * @template T of FromRow
	 * @param class-string<T> $class
	 * @return T
	 */
	public function asClass(string $class): mixed
	{
		return $class::fromDatabaseRow($this);
	}

}
