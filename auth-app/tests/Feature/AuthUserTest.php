<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->get('/api/user');
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
