<?php

use App\Models\Film;
use Faker\Generator as Faker;

$factory->define(Film::class, function (Faker $faker) {
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->text(300),
        'release_year' => $faker->numberBetween(1900, 2020),
        'created_by_user_id' => $faker->randomNumber(),
    ];
});
