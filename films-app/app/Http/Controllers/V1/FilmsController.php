<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmsController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'release_year' => 'required|integer|between:1000,2100',
        ]);

        $film = Film::make($validatedData);
        $film->created_by_user_id = $request->user()->id;
        $film->save();

        return response()->json($film, 201);
    }

    public function getSuggestions(Request $request)
    {
        return Film::getNonFavoriteQuery($request->user()->id)->paginate(10);
    }
}
