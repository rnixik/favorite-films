<?php

namespace Tests\Feature;

use App\Models\User;

trait ApiRequesterTrait
{
    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param int $userId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function requestApi(string $method, string $url, array $data, int $userId = 1)
    {
        $user = new User();
        $user->id = $userId;
        $response = $this->actingAs($user, 'api')
            ->$method("/api/v1/$url", $data, [
                'accept' => 'application/json',
            ]);
        return $response;
    }
}