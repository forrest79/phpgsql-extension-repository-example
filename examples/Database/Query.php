<?php declare(strict_types=1);

namespace App\Database;

use Forrest79\Database;

/**
 * @method Row|null fetch()
 * @method list<Row> fetchAll()
 */
final class Query extends Database\Fluent\Query
{
	private Joins|null $innerJoins = null;

	private Joins|null $leftJoins = null;



	public function joins(): Joins
	{
		if ($this->innerJoins === null) {
			$this->innerJoins = new Joins($this, Joins::INNER_JOIN);
		}

		return $this->innerJoins;
	}


	public function leftJoins(): Joins
	{
		if ($this->leftJoins === null) {
			$this->leftJoins = new Joins($this, Joins::LEFT_JOIN);
		}

		return $this->leftJoins;
	}


	/**
	 * @param class-string<Database\Repository> $repositoryClass
	 */
	public function joinRepository(
		string $repositoryClass,
		string $repositoryAlias,
		string $joinColumn,
		string|null $repositoryJoinColumn = null
	): static
	{
		return $this->createRepositoryJoin(
			Joins::INNER_JOIN,
			$repositoryClass,
			$repositoryAlias,
			$joinColumn,
			$repositoryJoinColumn,
		);
	}


	/**
	 * @param class-string<Database\Repository> $repositoryClass
	 */
	public function leftJoinRepository(
		string $repositoryClass,
		string $repositoryAlias,
		string $joinColumn,
		string|null $repositoryJoinColumn = null
	): static
	{
		return $this->createRepositoryJoin(
			Joins::LEFT_JOIN,
			$repositoryClass,
			$repositoryAlias,
			$joinColumn,
			$repositoryJoinColumn,
		);
	}


	/**
	 * @param class-string<Database\Repository> $repositoryClass
	 */
	private function createRepositoryJoin(
		int $joinType,
		string $repositoryClass,
		string $repositoryAlias,
		string $joinColumn,
		string|null $repositoryJoinColumn = null
	): static
	{
		$tableName = $repositoryClass::getTableName();
		$onCondition = sprintf(
			'%s.%s = %s',
			$repositoryAlias,
			$repositoryJoinColumn ?? $repositoryClass::getPrimaryKey(),
			$joinColumn,
		);

		if ($joinType === Joins::INNER_JOIN) {
			return $this->innerJoin($tableName, $repositoryAlias, $onCondition);
		}

		return $this->leftJoin($tableName, $repositoryAlias, $onCondition);
	}

}
