<?php

namespace Tests\Feature;

use App\Models\Film;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseTransactions;
    use ApiRequesterTrait;

    public function testStoreSuccess()
    {
        /** @var Film $film */
        $film = factory(Film::class)->create();
        $data = [
            'film_id' => $film->id,
        ];
        $response = $this->requestApi('post', 'favorites', $data, 333);
        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => 333,
            'film_id' => $film->id,
        ]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => 333,
            'film_id' => $film->id,
        ]);
    }

    public function testDoubleStoreSuccess()
    {
        /** @var Film $film */
        $film = factory(Film::class)->create();
        $data = [
            'film_id' => $film->id,
        ];
        $this->requestApi('post', 'favorites', $data, 333);
        $response = $this->requestApi('post', 'favorites', $data, 333);
        $response->assertStatus(200);
        $response->assertJson([
            'user_id' => 333,
            'film_id' => $film->id,
        ]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => 333,
            'film_id' => $film->id,
        ]);
    }

    public function testStoreNoneExistedFilm()
    {
        $data = [
            'film_id' => 999999999999,
        ];
        $response = $this->requestApi('post', 'favorites', $data);
        $response->assertStatus(422);
    }

    public function testStoreBadIdFilm()
    {
        $data = [
            'film_id' => 'testing_string',
        ];
        $response = $this->requestApi('post', 'favorites', $data);
        $response->assertStatus(422);
    }

    public function testStoreEmpty()
    {
        $data = [];
        $response = $this->requestApi('post', 'favorites', $data);
        $response->assertStatus(422);
    }
}
