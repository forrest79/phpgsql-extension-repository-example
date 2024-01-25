<?php declare(strict_types=1);

namespace App\Database;

use Forrest79\PhPgSql\Db;

final class RowFactory implements Db\RowFactory
{

	/**
	 * @param array<string, mixed> $rawValues
	 */
	public function createRow(Db\ColumnValueParser $columnValueParser, array $rawValues): Row
	{
		return new Row($columnValueParser, $rawValues);
	}

}
