<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $release_year
 * @property int|null $created_by_user_id
 *
 * @method static static findOrFail($id, $columns = ['*'])
 * @method static static make(array $attributes = [])
 * @method static Builder select($columns = ['*'])
 */
class Film extends Model
{
    protected $table = 'films';

    public $fillable = [
        'title',
        'description',
        'release_year',
    ];

    /**
     * @param int $userId
     * @return Builder
     */
    public static function getNonFavoriteQuery(int $userId)
    {
        return Film::select(['films.*'])
            ->leftJoin('favorites', function (JoinClause $join) use ($userId) {
                $join->on('films.id', '=', 'favorites.film_id')
                    ->where('favorites.user_id', $userId);
            })
            ->whereNull('favorites.id')
            ->orderBy('id', 'asc');
    }
}
