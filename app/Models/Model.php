<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @method static Builder<static> where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder<static> whereNull($columns, $boolean = 'and', $not = false)
 * @method static Collection<int, static> all(string[] $columns = '*')
 */
abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
}
