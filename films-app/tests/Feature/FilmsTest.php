<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleTest extends TestCase
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
}
