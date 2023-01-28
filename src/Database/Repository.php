<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql;

abstract class Repository
{
	private Connection $connection;


	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}


	public function table(string|NULL $alias = NULL): Fluent\Query
	{
		return $this->createQuery()->table(static::getTableName(), $alias);
	}


	/**
	 * @param array<string> $columns
	 */
	public function get(int $id, array $columns): PhPgSql\Db\Row|NULL
	{
		return $this->table()
			->select($columns)
			->where(static::getPrimaryKey(), $id)
			->limit(1)
			->fetch();
	}


	/**
	 * @param array<string, mixed> $data
	 */
	public function insert(array $data): PhPgSql\Db\Result
	{
		return $this->table()->values($data)->execute();
	}


	/**
	 * @param array<mixed> $data
	 * @param array<string> $returning
	 */
	public function insertReturning(array $data, array $returning): PhPgSql\Db\Row
	{
		$row = $this->table()->values($data)->returning($returning)->fetch();
		if ($row === NULL) {
			throw new Exceptions\DatabaseException('No row was inserted.');
		}
		return $row;
	}


	/**
	 * @param array<mixed> $data
	 */
	public function update(int $id, array $data): PhPgSql\Db\Result
	{
		return $this->table()->set($data)->wherePrimary($id)->execute();
	}


	/**
	 * @param array<mixed> $data
	 * @param array<string> $returning
	 */
	public function updateReturning(int $id, array $data, array $returning): PhPgSql\Db\Row
	{
		$row = $this->table()->set($data)->wherePrimary($id)->returning($returning)->fetch();
		if ($row === NULL) {
			throw new Exceptions\DatabaseException('No row was updated.');
		}
		return $row;
	}


	public function delete(int $id): PhPgSql\Db\Result
	{
		return $this->table()->delete()->wherePrimary($id)->execute();
	}


	/**
	 * @param array<string> $returning
	 */
	public function deleteReturning(int $id, array $returning): PhPgSql\Db\Row
	{
		$row = $this->table()->delete()->wherePrimary($id)->returning($returning)->fetch();
		if ($row === NULL) {
			throw new Exceptions\DatabaseException('No row was deleted.');
		}
		return $row;
	}


	public function getNextId(): int
	{
		$nextId = $this->connection()->select([sprintf('nextval(\'%s_id_seq\')', static::getTableName())])->fetchSingle();
		assert(is_int($nextId));
		return $nextId;
	}


	protected function connection(): Connection
	{
		return $this->connection;
	}


	protected function createQuery(): Fluent\Query
	{
		return $this->connection->createQuery()->setRepository($this);
	}


	abstract public static function getTableName(): string;


	public static function getPrimaryKey(): string
	{
		return 'id';
	}


	public static function getDefaultJoinColumn(): string
	{
		throw new Exceptions\DatabaseException(sprintf('%s repository has no default join column', static::class));
	}


	/**
	 * @param class-string<self> $repositoryClass
	 * @return array<string>
	 */
	public static function meta(string $repositoryClass, string|NULL $joinColumn): array
	{
		return [
			$repositoryClass::getTableName(),
			$joinColumn ?? $repositoryClass::getDefaultJoinColumn(),
		];
	}


	protected static function withAlias(string $column, string|NULL $alias): string
	{
		return Sql::withAlias($column, $alias);
	}

}
