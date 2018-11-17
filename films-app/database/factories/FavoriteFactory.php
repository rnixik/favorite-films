<?php

use App\Models\Favorite;
use App\Models\Film;
use Faker\Generator as Faker;

$factory->define(Favorite::class, function (Faker $faker, $attributes) {
    if (!empty($attributes['film_id'])) {
        $film = Film::findOrFail($attributes['film_id']);
    } else {
        $film = factory(Film::class)->create();
    }
    return [
        'user_id' => $faker->randomNumber(),
        'film_id' => $film->id,
    ];
});
