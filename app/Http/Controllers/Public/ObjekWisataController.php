<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\Kecamatan;
use App\Models\FasilitasWisata;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ObjekWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = ObjekWisata::with(['fotos', 'kecamatan'])
            ->withAvg('reviews as rating_avg', 'rating');

        if ($request->search) {
            $query->where('nama_objek', 'like', "%{$request->search}%");
        }

        if ($request->id_kecamatan) {
            $query->where('id_kecamatan', $request->id_kecamatan);
        }

        $objekWisatas = $query->latest()->paginate(12)->withQueryString();
        $kecamatans = Kecamatan::all();

        return Inertia::render('Public/ObjekWisata/Index', [
            'objekWisatas' => $objekWisatas,
            'kecamatans' => $kecamatans,
            'filters' => $request->only(['search', 'id_kecamatan']),
        ]);
    }

    public function show($slug)
    {
        $objekWisata = ObjekWisata::with(['fotos', 'kecamatan', 'fasilitas', 'reviews.user'])
            ->withAvg('reviews as rating_avg', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = \App\Models\Favorite::where('user_id', auth()->id())
                ->where('objek_wisata_id', $objekWisata->id)
                ->exists();
        }

        return Inertia::render('Public/ObjekWisata/Show', [
            'objekWisata' => $objekWisata,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function map()
    {
        $objekWisatas = \Illuminate\Support\Facades\Cache::remember('public.map.objek', 60 * 60, function () {
            return ObjekWisata::with(['fotos', 'kecamatan'])
                ->withAvg('reviews as rating_avg', 'rating')
                ->get();
        });

        $umkms = \Illuminate\Support\Facades\Cache::remember('public.map.umkm', 60 * 60, function () {
            return Umkm::with('fotos')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        });

        return Inertia::render('Public/Map', [
            'objekWisatas' => $objekWisatas,
            'umkms' => $umkms,
        ]);
    }
}
