<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $release_year
 * @property int|null $created_by_user_id
 *
 * @method static static findOrFail($id, $columns = ['*'])
 */
class Film extends Model
{
    protected $table = 'films';
}
