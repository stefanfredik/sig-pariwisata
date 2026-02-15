<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'objek_wisata_id' => 'required|exists:objek_wisatas,id',
        ]);

        $userId = Auth::id();
        $objekWisataId = $request->objek_wisata_id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('objek_wisata_id', $objekWisataId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
            $message = 'Dihapus dari favorit';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'objek_wisata_id' => $objekWisataId,
            ]);
            $status = 'added';
            $message = 'Ditambahkan ke favorit';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'is_favorited' => $status === 'added'
            ]);
        }

        return back()->with('success', $message);
    }
}
