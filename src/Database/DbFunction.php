<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql;

class DbFunction
{
	private Connection $connection;


	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}


	/**
	 * @param string|int|float|bool|array<mixed>|NULL ...$params
	 */
	public function run(string $function, mixed ...$params): PhPgSql\Db\Result
	{
		return $this->connection->queryArgs(
			sprintf('SELECT %s(%s)', $function, implode(', ', array_fill(0, count($params), '?'))),
			$params,
		);
	}

}
