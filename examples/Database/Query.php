<?php declare(strict_types=1);

namespace App\Database;

use Forrest79\Database;

/**
 * @method Row|NULL fetch()
 * @method list<Row> fetchAll()
 */
final class Query extends Database\Fluent\Query
{
	private Joins|NULL $innerJoins = NULL;

	private Joins|NULL $leftJoins = NULL;



	public function joins(): Joins
	{
		if ($this->innerJoins === NULL) {
			$this->innerJoins = new Joins($this, Joins::INNER_JOIN);
		}

		return $this->innerJoins;
	}


	public function leftJoins(): Joins
	{
		if ($this->leftJoins === NULL) {
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
		string|NULL $repositoryJoinColumn = NULL
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
		string|NULL $repositoryJoinColumn = NULL
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
		string|NULL $repositoryJoinColumn = NULL
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
