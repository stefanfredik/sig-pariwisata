<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ObjekWisata;
use App\Models\Foto;
use App\Repositories\Eloquent\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

class EventController extends Controller
{
    protected $eventRepo;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'id_objek', 'status']);
        $sortField = $request->get('sort_field', 'tanggal_mulai');
        $sortDirection = $request->get('sort_direction', 'desc');

        $events = $this->eventRepo->paginate(10, $filters, $sortField, $sortDirection);
        $objekWisatas = ObjekWisata::all();

        return Inertia::render('Admin/Event/Index', [
            'events' => $events,
            'objekWisatas' => $objekWisatas,
            'filters' => array_merge($filters, [
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Event/Create', [
            'objekWisatas' => ObjekWisata::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_objek' => 'nullable|exists:objek_wisatas,id',
            'nama_event' => 'required|string|max:100|unique:events',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $event = $this->eventRepo->create($validated);

        // Handle File Uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'fotos/events/' . $filename;

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, $image->encode());

                $event->fotos()->create([
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        Cache::forget('public.home.data');

        return redirect()->route('admin.events.index')
            ->with('message', 'Event berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = $this->eventRepo->findWithDetails($id);

        return Inertia::render('Admin/Event/Edit', [
            'event' => $event,
            'objekWisatas' => ObjekWisata::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = $this->eventRepo->find($id);

        $validated = $request->validate([
            'id_objek' => 'nullable|exists:objek_wisatas,id',
            'nama_event' => 'required|string|max:100|unique:events,nama_event,' . $id,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->eventRepo->update($id, $validated);

        // Handle New File Uploads
        if ($request->hasFile('new_fotos')) {
            foreach ($request->file('new_fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'fotos/events/' . $filename;

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, $image->encode());

                $event->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.home.data');

        return redirect()->route('admin.events.index')
            ->with('message', 'Event berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = $this->eventRepo->find($id);

        foreach ($event->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
            $foto->delete();
        }

        $this->eventRepo->delete($id);

        Cache::forget('public.home.data');

        return redirect()->route('admin.events.index')
            ->with('message', 'Event berhasil dihapus.');
    }

    /**
     * Delete a specific photo.
     */
    public function deletePhoto(string $id)
    {
        $foto = Foto::findOrFail($id);
        Storage::disk('public')->delete($foto->path);
        $foto->delete();

        Cache::forget('public.home.data');

        return back()->with('message', 'Foto berhasil dihapus.');
    }

    /**
     * Set a photo as primary.
     */
    public function setPrimaryPhoto(string $eventId, string $fotoId)
    {
        $event = Event::findOrFail($eventId);
        $event->fotos()->update(['is_primary' => false]);

        $foto = Foto::findOrFail($fotoId);
        $foto->update(['is_primary' => true]);

        Cache::forget('public.home.data');

        return back()->with('message', 'Foto utama berhasil diupdate.');
    }
}
