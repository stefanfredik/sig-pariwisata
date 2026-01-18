<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Review;

class ProfileController extends Controller
{
    /**
     * Display the user's profile and reviews.
     */
    public function show()
    {
        $user = Auth::user();
        $reviews = Review::with(['objekWisata', 'fotos'])
            ->where('id_user', $user->id)
            ->latest()
            ->paginate(5);

        return Inertia::render('Public/Profile', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('message', 'Profil berhasil diperbarui.');
    }
}
