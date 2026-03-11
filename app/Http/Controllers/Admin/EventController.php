<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ObjekWisata;
use App\Models\Foto;
use App\Repositories\Eloquent\EventRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
    public function index()
    {
        $events = Event::with(['objekWisata', 'fotos'])->latest()->paginate(10);
        return Inertia::render('Admin/Event/Index', [
            'events' => $events
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objekWisatas = ObjekWisata::select('id', 'nama_objek')->get();
        return Inertia::render('Admin/Event/Create', [
            'objekWisatas' => $objekWisatas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_objek' => 'nullable|exists:objek_wisatas,id',
            'nama_event' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        $event = $this->eventRepo->create($validated);

        // Handle File Uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/events/' . $filename;

                // Ensure directory exists
                if (!Storage::disk('public')->exists('fotos/events')) {
                    Storage::disk('public')->makeDirectory('fotos/events');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with(['objekWisata', 'fotos'])->findOrFail($id);
        return Inertia::render('Admin/Event/Show', [
            'event' => $event
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::with('fotos')->findOrFail($id);
        $objekWisatas = ObjekWisata::select('id', 'nama_objek')->get();
        
        return Inertia::render('Admin/Event/Edit', [
            'event' => $event,
            'objekWisatas' => $objekWisatas
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_objek' => 'nullable|exists:objek_wisatas,id',
            'nama_event' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $id,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        $this->eventRepo->update($id, $validated);

        // Handle New File Uploads
        if ($request->hasFile('new_fotos')) {
            $event = Event::findOrFail($id);
            foreach ($request->file('new_fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/events/' . $filename;

                // Ensure directory exists
                if (!Storage::disk('public')->exists('fotos/events')) {
                    Storage::disk('public')->makeDirectory('fotos/events');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $event->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.home.data');

        return redirect()->route('admin.events.index')
            ->with('message', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        
        foreach ($event->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $this->eventRepo->delete($id);

        Cache::forget('public.home.data');

        return redirect()->route('admin.events.index')
            ->with('message', 'Event berhasil dihapus.');
    }

    /**
     * Add photos directly.
     */
    public function addPhoto(Request $request, $id)
    {
        $request->validate([
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        $event = Event::findOrFail($id);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/events/' . $filename;

                if (!Storage::disk('public')->exists('fotos/events')) {
                    Storage::disk('public')->makeDirectory('fotos/events');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $event->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.home.data');

        return back()->with('message', 'Foto berhasil ditambahkan.');
    }

    /**
     * Delete a photo.
     */
    public function deletePhoto($id)
    {
        $foto = Foto::findOrFail($id);
        Storage::disk('public')->delete($foto->path);
        $foto->delete();

        Cache::forget('public.home.data');

        return back()->with('message', 'Foto berhasil dihapus.');
    }

    /**
     * Set primary photo.
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
