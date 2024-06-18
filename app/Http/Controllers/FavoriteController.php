<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Favorite $favorites)
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('product')->get();

        return view('favorites', compact('favorites'));
    }
}
