<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Review;
use App\Models\User;
use Inertia\Inertia;

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
            'jumlah_kunjungan' => ObjekWisata::sum('view_count'),
            'review_pending' => Review::where('status', 'pending')->count(),
            'review_approved' => Review::where('status', 'approved')->count(),
        ];

        // 2. Visitors Trend (Dummy Data via DB raw or fixed for now)
        // In production, track this via middleware logging to a DB table
        $visitorTrends = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [120, 150, 180, 220, 300, 280],
        ];

        // 3. Kunjungan per Objek Wisata (sorted descending)
        $objekWisataKunjungan = ObjekWisata::query()
            ->with('kecamatan:id,nama_kecamatan')
            ->orderByDesc('view_count')
            ->orderBy('nama_objek')
            ->get(['id', 'id_kecamatan', 'nama_objek', 'view_count']);

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
            'objekWisataKunjungan' => $objekWisataKunjungan,
            'recentReviews' => $recentReviews,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
