<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ObjekWisata;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['fotos', 'objekWisata']);

        if ($request->search) {
            $query->where('nama_event', 'like', "%{$request->search}%");
        }

        if ($request->id_objek) {
            $query->where('id_objek', $request->id_objek);
        }

        if ($request->status) {
            if ($request->status === 'upcoming')
                $query->upcoming();
            elseif ($request->status === 'ongoing')
                $query->ongoing();
            elseif ($request->status === 'past')
                $query->past();
        }

        $events = $query->orderBy('tanggal_mulai', 'asc')->paginate(12)->withQueryString();
        $objekWisatas = ObjekWisata::all();

        return Inertia::render('Public/Event/Index', [
            'events' => $events,
            'objekWisatas' => $objekWisatas,
            'filters' => $request->only(['search', 'id_objek', 'status']),
        ]);
    }

    public function show($slug)
    {
        $event = Event::with(['fotos', 'objekWisata'])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Event/Show', [
            'event' => $event,
        ]);
    }
}
