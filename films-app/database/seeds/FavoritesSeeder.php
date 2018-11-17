<?php

use Illuminate\Database\Seeder;

class FavoritesSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $userId1 = 1;
        factory(\App\Models\Favorite::class, 10)->create([
            'user_id' => $userId1,
        ]);

        $userId2 = 2;
        factory(\App\Models\Favorite::class, 5)->create([
            'user_id' => $userId2,
        ]);

        factory(\App\Models\Favorite::class, 15)->create();
    }
}
