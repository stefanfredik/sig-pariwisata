<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\Kecamatan;
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

        return Inertia::render('Public/ObjekWisata/Show', [
            'objekWisata' => $objekWisata,
        ]);
    }

    public function map()
    {
        $objekWisatas = ObjekWisata::with(['fotos', 'kecamatan'])
            ->withAvg('reviews as rating_avg', 'rating')
            ->get();

        return Inertia::render('Public/Map', [
            'objekWisatas' => $objekWisatas,
        ]);
    }
}
