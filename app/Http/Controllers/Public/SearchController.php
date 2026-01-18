<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\Event;

class SearchController extends Controller
{
    /**
     * Provide suggestions for global search.
     */
    public function suggest(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $objects = ObjekWisata::where('nama_objek', 'LIKE', "%{$query}%")
            ->take(5)
            ->get(['id', 'nama_objek as title', 'slug', 'id_kecamatan'])
            ->map(function ($item) {
                $item->type = 'Destinasi';
                $item->url = route('public.objek-wisata.show', $item->slug);
                return $item;
            });

        $events = Event::where('nama_event', 'LIKE', "%{$query}%")
            ->take(3)
            ->get(['id', 'nama_event as title', 'slug'])
            ->map(function ($item) {
                $item->type = 'Event';
                $item->url = route('public.events.show', $item->slug);
                return $item;
            });

        return response()->json($objects->merge($events));
    }
}
