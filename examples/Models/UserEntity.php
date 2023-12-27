<?php declare(strict_types=1);

namespace App\Models;

use Forrest79\Database;

final class UserEntity implements Database\FromRow
{

	public function __construct(
		public int $id,
		public string $nick,
		public bool $active,
	)
	{

	}


	static function fromDatabaseRow(Database\Row $row): static
	{
		return new static(
			$row->value('id')->getInt(),
			$row->value('nick')->getString(),
			$row->value('active')->getBool(),
		);
	}

}
