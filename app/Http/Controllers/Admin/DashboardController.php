<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats Cards Data
        $stats = [
            'kecamatan' => Kecamatan::count(),
            'objek_wisata' => ObjekWisata::count(),
            'event' => Event::count(),
            'user' => User::where('role', 'user')->count(),
            'review_pending' => Review::where('status', 'pending')->count(),
            'review_approved' => Review::where('status', 'approved')->count(),
        ];

        // 2. Visitors Trend (Dummy Data via DB raw or fixed for now)
        // In production, track this via middleware logging to a DB table
        $visitorTrends = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [120, 150, 180, 220, 300, 280],
        ];

        // 3. Top 5 Objek Wisata by Rating
        $topDestinations = ObjekWisata::orderBy('rating_avg', 'desc')
            ->orderBy('review_count', 'desc')
            ->take(5)
            ->get(['id', 'nama_objek', 'rating_avg', 'review_count']);

        // 4. Recent Pending Reviews (Priority)
        $recentReviews = Review::with(['user', 'objekWisata'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // 5. Upcoming Events
        $upcomingEvents = Event::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'visitorTrends' => $visitorTrends,
            'topDestinations' => $topDestinations,
            'recentReviews' => $recentReviews,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
