<?php declare(strict_types=1);

namespace Forrest79\Database\Fluent;

use Forrest79\Database;
use Forrest79\PhPgSql;

class Query extends PhPgSql\Fluent\QueryExecute
{
	private Database\Connection $connection;

	private Database\Repository|NULL $repository = NULL;


	public function __construct(PhPgSql\Fluent\QueryBuilder $queryBuilder, Database\Connection $connection)
	{
		parent::__construct($queryBuilder, $connection);
		$this->connection = $connection;
	}


	protected function getConnection(): Database\Connection
	{
		return $this->connection;
	}


	public function setRepository(Database\Repository $repository): static
	{
		$this->repository = $repository;
		return $this;
	}


	protected function getRepository(): Database\Repository
	{
		if ($this->repository === NULL) {
			throw new Database\Exceptions\DatabaseException('No repository is set');
		}

		return $this->repository;
	}


	public function wherePrimary(mixed $value, string|NULL $tableAlias = NULL): static
	{
		return $this->where(Database\Sql::withAlias($this->getRepository()::getPrimaryKey(), $tableAlias), $value);
	}


	public function count(): int
	{
		if ($this->has(self::PARAM_GROUPBY)) {
			throw new Database\Exceptions\DatabaseException('Count is not working for query with GROUP BY, you must prepare counting query manually.');
		}

		$query = (clone $this)
			->reset(self::PARAM_SELECT)
			->reset(self::PARAM_LIMIT)
			->reset(self::PARAM_OFFSET)
			->reset(self::PARAM_ORDERBY);

		/** @phpstan-var int<0, max> */
		return $query->select(['count(*)'])->fetchSingle();
	}


	public function exists(): bool
	{
		return (bool) $this->connection
			->query('SELECT EXISTS (?)', $this->select(['TRUE'])->createSqlQuery())
			->fetchSingle();
	}


	public function wrap(string $alias): self
	{
		return $this->getConnection()->createQuery()->from($this, $alias);
	}


	/**
	 * @param string $column !IMPORTANT: you need to be sure, that here can't be SQL injection, this parameter is directly injected to SQL query
	 * @param list<int> $keys
	 */
	public function orderByValues(string $column, array $keys): static
	{
		if ($keys === []) {
			throw new Database\Exceptions\DatabaseException('Keys to sort against in array_position can not be empty.');
		}

		$keys = array_map('intval', $keys);
		return $this->orderBy(sprintf(
			'array_position(ARRAY[%s], %s)',
			implode(', ', $keys),
			$column,
		));
	}


	public function fetchSingleValue(): Database\Value
	{
		return new Database\Value($this->fetchSingle());
	}

}
