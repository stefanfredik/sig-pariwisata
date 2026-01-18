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
        // Fetch popular/featured objects
        $popularObjects = ObjekWisata::with(['fotos', 'kecamatan'])
            ->withAvg('reviews as rating_avg', 'rating')
            ->orderByDesc('rating_avg')
            ->limit(4)
            ->get();

        // Fetch upcoming events
        $upcomingEvents = Event::with('fotos', 'objekWisata')
            ->upcoming()
            ->limit(3)
            ->get();

        // Get some stats
        $stats = [
            'kecamatan_count' => Kecamatan::count(),
            'objek_count' => ObjekWisata::count(),
            'event_count' => Event::count(),
        ];

        return Inertia::render('Welcome', [
            'popularObjects' => $popularObjects,
            'upcomingEvents' => $upcomingEvents,
            'stats' => $stats,
        ]);
    }
}
