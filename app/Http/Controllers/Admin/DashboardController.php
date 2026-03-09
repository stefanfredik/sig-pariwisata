<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Review;
use App\Models\Umkm;
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

        // 6. UMKM terdekat per objek wisata (paginated)
        $umkmTerdekatTable = ObjekWisata::query()
            ->select(['id', 'nama_objek', 'latitude', 'longitude'])
            ->orderBy('nama_objek')
            ->paginate(10, ['*'], 'umkm_page')
            ->withQueryString();

        $umkmTerdekatTable->setCollection(
            $umkmTerdekatTable->getCollection()->map(function ($objek) {
                $latitude = (float) $objek->latitude;
                $longitude = (float) $objek->longitude;

                $umkmTerdekat = Umkm::query()
                    ->select(['id', 'nama_umkm', 'kategori'])
                    ->selectRaw(
                        'ROUND(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude))), 2) as jarak_km',
                        [$latitude, $longitude, $latitude]
                    )
                    ->orderByRaw('POW(latitude - ?, 2) + POW(longitude - ?, 2)', [$latitude, $longitude])
                    ->first();

                return [
                    'id_objek' => $objek->id,
                    'nama_objek' => $objek->nama_objek,
                    'umkm' => $umkmTerdekat ? [
                        'id' => $umkmTerdekat->id,
                        'nama_umkm' => $umkmTerdekat->nama_umkm,
                        'kategori' => $umkmTerdekat->kategori,
                        'jarak_km' => $umkmTerdekat->jarak_km,
                    ] : null,
                ];
            })
        );

        // 7. Daya tarik wisata per kecamatan (paginated)
        $dayaTarikPerKecamatanTable = Kecamatan::query()
            ->withCount('objekWisatas')
            ->with([
                'objekWisatas' => function ($query) {
                    $query->select(['id', 'id_kecamatan', 'nama_objek', 'daya_tarik_utama'])
                        ->whereNotNull('daya_tarik_utama')
                        ->where('daya_tarik_utama', '!=', '')
                        ->orderBy('nama_objek');
                },
            ])
            ->orderBy('nama_kecamatan')
            ->paginate(10, ['*'], 'daya_tarik_page')
            ->withQueryString();

        $dayaTarikPerKecamatanTable->setCollection(
            $dayaTarikPerKecamatanTable->getCollection()->map(function ($kecamatan) {
                return [
                    'id_kecamatan' => $kecamatan->id,
                    'nama_kecamatan' => $kecamatan->nama_kecamatan,
                    'total_objek' => $kecamatan->objek_wisatas_count,
                    'daya_tarik_items' => $kecamatan->objekWisatas->map(function ($objek) {
                        return [
                            'id_objek' => $objek->id,
                            'nama_objek' => $objek->nama_objek,
                            'daya_tarik_utama' => $objek->daya_tarik_utama,
                        ];
                    })->values(),
                ];
            })
        );

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'visitorTrends' => $visitorTrends,
            'objekWisataKunjungan' => $objekWisataKunjungan,
            'recentReviews' => $recentReviews,
            'upcomingEvents' => $upcomingEvents,
            'umkmTerdekatTable' => $umkmTerdekatTable,
            'dayaTarikPerKecamatanTable' => $dayaTarikPerKecamatanTable,
        ]);
    }
}
