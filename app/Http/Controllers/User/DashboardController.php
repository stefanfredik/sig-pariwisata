<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. My Reviews
        $myReviews = Review::with('objekWisata')
            ->where('id_user', $user->id) // Note: user relation in Review model uses 'id_user' FK usually, need to check migration/model
            ->latest()
            ->get();

        // 2. My Favorites
        $myFavorites = Favorite::with('objekWisata') // load objek wisata details
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 3. Stats
        $stats = [
            'reviews_count' => $myReviews->count(),
            'favorites_count' => $myFavorites->count(),
        ];

        return Inertia::render('User/Dashboard', [
            'reviews' => $myReviews,
            'favorites' => $myFavorites,
            'stats' => $stats,
        ]);
    }
}
