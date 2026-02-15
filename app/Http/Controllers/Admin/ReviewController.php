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

        if ($request->search) {
            $query->where('komentar', 'like', "%{$request->search}%")
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $reviews = $query->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Review/Index', [
            'reviews' => $reviews,
            'filters' => array_merge($request->only(['search', 'status']), [
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ]),
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
