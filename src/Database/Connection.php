<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql;

class Connection extends PhPgSql\Fluent\Connection
{
	private Transaction|NULL $transaction = NULL;


	/**
	 * @return Transaction
	 */
	public function transaction(): PhPgSql\Db\Transaction
	{
		if ($this->transaction === NULL) {
			$this->transaction = new Transaction($this);
		}
		return $this->transaction;
	}


	/**
	 * @return Fluent\Query
	 */
	public function createQuery(): PhPgSql\Fluent\QueryExecute
	{
		return new Fluent\Query($this->getQueryBuilder(), $this);
	}


	public function setStatementTimeout(int $second): void
	{
		$this->query(sprintf('SET statement_timeout = %d', $second * 1000));
	}

}
