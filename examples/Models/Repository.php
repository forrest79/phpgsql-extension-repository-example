<?php declare(strict_types=1);

namespace App\Models;

use App\Database\Query;
use App\Database\Row;
use Forrest79\Database;

/**
 * @method Query table(string|null $alias = NULL)
 * @method Row get(int $id, array $columns)
 * @method Row insertReturning(array $data, array $returning)
 * @method Row updateReturning(int $id, array $data, array $returning)
 * @method Row deleteReturning(int $id, array $returning)
 * @method Query createQuery()
 */
abstract class Repository extends Database\Repository
{

}
