<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Event;
use App\Models\Review;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_kecamatan' => Kecamatan::count(),
            'total_objek_wisata' => ObjekWisata::count(),
            'total_events' => Event::count(),
            'total_reviews' => Review::count(),
            'total_users' => User::where('role', 'user')->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
        ];

        $recent_reviews = Review::with(['user', 'objekWisata'])
            ->latest()
            ->take(5)
            ->get();

        $upcoming_events = Event::upcoming()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recent_reviews' => $recent_reviews,
            'upcoming_events' => $upcoming_events,
        ]);
    }
}
