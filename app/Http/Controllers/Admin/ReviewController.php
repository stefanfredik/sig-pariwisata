<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'objekWisata', 'fotos']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Review/Index', [
            'reviews' => $reviews,
            'filters' => $request->only(['status']),
        ]);
    }

    /**
     * Approve the specified review.
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->approve(Auth::id());

        return back()->with('message', 'Review berhasil disetujui.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Delete photos from storage
        foreach ($review->fotos as $foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($foto->path);
            $foto->delete();
        }

        $review->delete();

        return back()->with('message', 'Review berhasil dihapus.');
    }
}
