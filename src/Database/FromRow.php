<?php declare(strict_types=1);

namespace Forrest79\Database;

interface FromRow
{

	static function fromDatabaseRow(Row $row): static;

}
