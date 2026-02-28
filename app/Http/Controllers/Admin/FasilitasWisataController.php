<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\Foto;
use App\Models\FasilitasWisata;
use App\Repositories\Eloquent\FasilitasWisataRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

class FasilitasWisataController extends Controller
{
    protected $fasilitasRepo;

    public function __construct(FasilitasWisataRepository $fasilitasRepo)
    {
        $this->fasilitasRepo = $fasilitasRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'id_objek']);
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $fasilitas = $this->fasilitasRepo->paginate(10, $filters, $sortField, $sortDirection);

        return Inertia::render('Admin/FasilitasWisata/Index', [
            'fasilitas' => $fasilitas,
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
        return Inertia::render('Admin/FasilitasWisata/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_objek' => 'required|exists:objek_wisatas,id',
            'nama_fasilitas' => 'required|string|max:50',
            'kategori_fasilitas' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fasilitas = $this->fasilitasRepo->create($validated);

        // Handle File Uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'fotos/fasilitas/' . $filename;

                // Resize and Save
                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, $image->encode());

                $fasilitas->fotos()->create([
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        Cache::forget('public.map.data');

        return redirect()->route('admin.objek-wisata.show', $validated['id_objek'])
            ->with('message', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fasilitas = $this->fasilitasRepo->find($id);
        $fasilitas->load(['objekWisata', 'fotos']);

        return Inertia::render('Admin/FasilitasWisata/Show', [
            'fasilitas' => $fasilitas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fasilitas = $this->fasilitasRepo->find($id);
        $fasilitas->load('fotos');

        return Inertia::render('Admin/FasilitasWisata/Edit', [
            'fasilitas' => $fasilitas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_objek' => 'required|exists:objek_wisatas,id',
            'nama_fasilitas' => 'required|string|max:50',
            'kategori_fasilitas' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->fasilitasRepo->update($id, $validated);
        $fasilitas = $this->fasilitasRepo->find($id);

        // Handle New File Uploads
        if ($request->hasFile('new_fotos')) {
            foreach ($request->file('new_fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'fotos/fasilitas/' . $filename;

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, $image->encode());

                $fasilitas->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.map.data');

        return redirect()->route('admin.objek-wisata.show', $validated['id_objek'])
            ->with('message', 'Fasilitas berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fasilitas = FasilitasWisata::with('fotos')->findOrFail($id);

        // Delete physical photos
        foreach ($fasilitas->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
            $foto->delete();
        }

        $fasilitas->delete();

        Cache::forget('public.map.data');

        return redirect()->route('admin.objek-wisata.show', $fasilitas->id_objek)
            ->with('message', 'Fasilitas berhasil dihapus.');
    }

    /**
     * Delete a specific photo.
     */
    public function deletePhoto(string $id)
    {
        $foto = Foto::findOrFail($id);
        Storage::disk('public')->delete($foto->path);
        $foto->delete();

        Cache::forget('public.map.data');

        return back()->with('message', 'Foto berhasil dihapus.');
    }
}
