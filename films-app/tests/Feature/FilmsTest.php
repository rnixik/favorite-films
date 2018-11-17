<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Film;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FilmsTest extends TestCase
{
    use DatabaseTransactions;
    use ApiRequesterTrait;

    public function testStoreSuccess()
    {
        $filmData = [
            'title' => 'Testing title',
            'description' => 'Testing description',
            'release_year' => 2013
        ];
        $response = $this->requestApi('post', 'films', $filmData, 333);
        $response->assertStatus(201);
        $response->assertJson([
            'title' => 'Testing title',
            'description' => 'Testing description',
            'release_year' => 2013,
            'created_by_user_id' => 333,
        ]);
        $this->assertDatabaseHas('films', $filmData);
    }

    /**
     * @dataProvider  storeValidationFailDataProvider
     * @param $filmData
     */
    public function testStoreValidationFail($filmData)
    {
        $response = $this->requestApi('post', 'films', $filmData);
        $response->assertStatus(422);
    }

    public function storeValidationFailDataProvider()
    {
        return [
            [[
                'title' => 'Testing title',
                'description' => 'Testing description',
            ]],
            [[
                'title' => 'Testing title',
                'release_year' => 2013
            ]],
            [[
                'description' => 'Testing description',
                'release_year' => 2013
            ]],
            [[
                'title' => 'Testing title',
                'description' => 'Testing description',
                'release_year' => 'some'
            ]],
        ];
    }

    public function testSuggestions()
    {
        // It's ok, we are in transaction
        \DB::table('films')->delete();

        /** @var Film[] $films */
        $films = factory(Film::class, 3)->create();
        $userId = 99999;

        $favorite = new Favorite();
        $favorite->user_id = $userId;
        $favorite->film_id = $films[1]->id;
        $favorite->save();

        $response = $this->requestApi('get', 'films/suggestions', [], $userId);
        $response->assertStatus(200);
        $actualFilms = $response->json('data');
        $this->assertEquals(2, count($actualFilms));
        $this->assertEquals($films[0]->id, $actualFilms[0]['id']);
        $this->assertEquals($films[2]->id, $actualFilms[1]['id']);
    }
}
