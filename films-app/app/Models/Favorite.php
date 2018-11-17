<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $film_id
 * @property Carbon $created
 * @property Carbon $updated_at
 *
 * @method static static findOrFail($id, $columns = ['*'])
 * @method static static make(array $attributes = [])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Favorite extends Model
{
    protected $table = 'favorites';
}