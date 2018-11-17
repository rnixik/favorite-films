<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'film_id' => 'required|integer|exists:films,id',
        ]);

        $userId = (int) $request->user()->id;
        $filmId = (int) $validatedData['film_id'];

        $favorite = Favorite::where('user_id', $userId)->where('film_id', $filmId)->first();

        if (!$favorite) {
            $favorite = new Favorite();
            $favorite->user_id = $request->user()->id;
            $favorite->film_id = (int) $validatedData['film_id'];
        }

        $favorite->updated_at = now();
        $favorite->save();

        return response()->json($favorite, $favorite->wasRecentlyCreated ? 201 : 200);
    }
}
