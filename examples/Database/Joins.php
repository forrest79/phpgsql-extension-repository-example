<?php declare(strict_types=1);

namespace App\Database;

use App\Models;

final class Joins
{
	public const INNER_JOIN = 1;
	public const LEFT_JOIN = 2;

	private Query $query;

	private int $joinType;


	public function __construct(Query $query, int $joinType)
	{
		if (!in_array($joinType, [self::INNER_JOIN, self::LEFT_JOIN], TRUE)) {
			throw new \RuntimeException(sprintf('Bad $joinType: %d.', $joinType));
		}

		$this->query = $query;
		$this->joinType = $joinType;
	}


	/**
	 * @param class-string<Models\Repository> $repositoryClass
	 */
	private function joinRepository(
		string $repositoryClass,
		string $repositoryAlias,
		string $joinColumn,
		?string $repositoryJoinColumn = NULL
	): Query
	{
		if ($this->joinType === self::INNER_JOIN) {
			return $this->query->joinRepository($repositoryClass, $repositoryAlias, $joinColumn, $repositoryJoinColumn);
		} else {
			return $this->query->leftJoinRepository(
				$repositoryClass,
				$repositoryAlias,
				$joinColumn,
				$repositoryJoinColumn,
			);
		}
	}


	public function departments(string $alias, string $joinColumn): Query
	{
		return $this->joinRepository(
			Models\Department::class,
			$alias,
			$joinColumn,
		);
	}


	public function userDepartmentsByUserId(string $alias, string $joinColumn): Query
	{
		return $this->joinRepository(
			Models\UserDepartment::class,
			$alias,
			$joinColumn,
			'user_id',
		);
	}


	public function users(string $alias, string $joinColumn): Query
	{
		return $this->joinRepository(
			Models\User::class,
			$alias,
			$joinColumn,
		);
	}

}
