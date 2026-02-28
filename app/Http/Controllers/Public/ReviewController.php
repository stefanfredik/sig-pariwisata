<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Review store called', [
            'user_id' => \Auth::id(),
            'data' => $request->except('fotos'),
        ]);

        try {
            $validated = $request->validate([
                'id_objek' => 'required|exists:objek_wisatas,id',
                'rating' => 'required|integer|min:1|max:5',
                'judul' => 'nullable|string|max:100',
                'komentar' => 'required|string|max:1000',
                'fotos' => 'nullable|array|max:5',
                'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:10240',
            ]);

            \Log::info('Validation passed');

            // Check if user already reviewed this object
            $existingReview = Review::where('id_user', Auth::id())
                ->where('id_objek', $request->id_objek)
                ->first();

            if ($existingReview) {
                \Log::info('Review already exists', ['review_id' => $existingReview->id]);
                return back()->with('error', 'Anda sudah memberikan review untuk objek wisata ini.');
            }

            $review = Review::create([
                'id_user' => Auth::id(),
                'id_objek' => $request->id_objek,
                'rating' => $request->rating,
                'judul' => $request->judul,
                'komentar' => $request->komentar,
                'status' => 'pending',
            ]);

            \Log::info('Review created', ['review_id' => $review->id]);

            // Handle photos if any
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $path = $file->store('reviews', 'public');
                    $review->fotos()->create(['path' => $path]);
                }
                \Log::info('Photos saved');
            }

            return back()->with('message', 'Terima kasih! Review Anda telah dikirim dan menunggu moderasi admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Review store error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }
}
