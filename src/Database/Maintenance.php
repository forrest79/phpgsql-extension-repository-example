<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql\Db;

class Maintenance
{
	private Connection $connection;


	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}


	public function vacuumAnalyze(): void
	{
		foreach ($this->listTables() as $table) {
			assert(is_string($table->name));
			$this->vacuumAnalyzeTable($table->name);
		}
	}


	private function vacuumAnalyzeTable(string $table): void
	{
		echo sprintf('Vacuum analyze "%s" ... ', $table);

		$this->connection->execute(sprintf('VACUUM ANALYZE %s', $table));

		echo 'DONE' . PHP_EOL;
	}


	private function listTables(): Db\Result
	{
		return $this->connection->query('SELECT table_schema || \'.\' || table_name AS name FROM information_schema.tables WHERE table_schema IN (\'public\', \'audit\') ORDER BY table_schema, table_name');
	}

}
