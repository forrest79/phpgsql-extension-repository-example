<?php declare(strict_types=1);

namespace App\Database;

use Forrest79\Database;
use Forrest79\PhPgSQl;

final class Connection extends Database\Connection
{

	public function createQuery(): Query
	{
		return new Query($this->getQueryBuilder(), $this);
	}

}
