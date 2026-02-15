<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\Event;
use App\Models\Kecamatan;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $data = \Illuminate\Support\Facades\Cache::remember('public.home.data', 60 * 60, function () {
            return [
                'popularObjects' => ObjekWisata::with(['fotos', 'kecamatan'])
                    ->withAvg('reviews as rating_avg', 'rating')
                    ->orderByDesc('rating_avg')
                    ->limit(4)
                    ->get(),
                'upcomingEvents' => Event::with('fotos', 'objekWisata')
                    ->upcoming()
                    ->limit(3)
                    ->get(),
                'stats' => [
                    'kecamatan_count' => Kecamatan::count(),
                    'objek_count' => ObjekWisata::count(),
                    'event_count' => Event::count(),
                ],
            ];
        });

        return Inertia::render('Welcome', [
            'popularObjects' => $data['popularObjects'],
            'upcomingEvents' => $data['upcomingEvents'],
            'stats' => $data['stats'],
        ]);
    }
}
