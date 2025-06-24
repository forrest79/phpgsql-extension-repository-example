<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql;

class Connection extends PhPgSql\Fluent\Connection
{
	private Transaction|null $transaction = null;


	public function transaction(): Transaction
	{
		if ($this->transaction === null) {
			$this->transaction = new Transaction($this);
		}
		return $this->transaction;
	}


	public function createQuery(): Fluent\Query
	{
		return new Fluent\Query($this->getQueryBuilder(), $this);
	}


	public function setStatementTimeout(int $second): void
	{
		$this->query(sprintf('SET statement_timeout = %d', $second * 1000));
	}

}
