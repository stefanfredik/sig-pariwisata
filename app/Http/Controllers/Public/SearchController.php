<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ObjekWisata;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // Search Objek Wisata
        $objekWisatas = ObjekWisata::where('nama_objek', 'like', "%{$query}%")
            ->orWhere('alamat', 'like', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->nama_objek,
                    'type' => 'Destinasi',
                    'url' => route('public.objek-wisata.show', $item->slug),
                ];
            });

        // Search Events
        $events = Event::where('nama_event', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->nama_event,
                    'type' => 'Event',
                    'url' => route('public.events.show', $item->slug),
                ];
            });

        $results = $results->merge($objekWisatas)->merge($events);

        return response()->json($results);
    }
}
