<?php declare(strict_types=1);

namespace App\Database;

use Forrest79\Database;
use Forrest79\PhPgSQl;

final class Connection extends Database\Connection
{

	/**
	 * @return Query
	 */
	public function createQuery(): PhPgSql\Fluent\QueryExecute
	{
		return new Query($this->getQueryBuilder(), $this);
	}

}
