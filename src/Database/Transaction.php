<?php declare(strict_types=1);

namespace Forrest79\Database;

use Forrest79\PhPgSql;

final class Transaction extends PhPgSql\Db\Transaction
{
	private bool $useSavepoints;

	private int $id = 0;


	public function __construct(Connection $connection, bool $useSavepoints = FALSE)
	{
		parent::__construct($connection);
		$this->useSavepoints = $useSavepoints;
	}


	public function execute(callable $callback, ?string $mode = NULL): mixed
	{
		$this->start($mode);
		try {
			$return = call_user_func($callback, $this->connection);
			$this->complete();
			return $return;
		} catch (\Throwable $e) {
			$this->cancel();
			throw $e;
		}
	}


	private function start(?string $mode = NULL): void
	{
		if ($this->connection->isInTransaction()) {
			if ($mode !== NULL) {
				throw new Exceptions\DatabaseException('You can\'t use mode for subtransactions.');
			}

			if ($this->useSavepoints) {
				$this->savepoint($this->getSavepoint());
			}
		} else {
			$this->begin($mode);
		}
		++$this->id;
	}


	private function complete(): void
	{
		--$this->id;
		if ($this->id > 0) {
			if ($this->useSavepoints) {
				$this->releaseSavepoint($this->getSavepoint());
			}
		} else {
			$this->commit();
		}
	}


	private function cancel(): void
	{
		--$this->id;
		if ($this->id > 0) {
			if ($this->useSavepoints) {
				$this->rollbackToSavepoint($this->getSavepoint());
			}
		} else {
			$this->rollback();
		}
	}


	private function getSavepoint(): string
	{
		return 'id_' . $this->id;
	}

}
