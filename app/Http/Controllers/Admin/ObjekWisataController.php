<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Foto;
use App\Repositories\Eloquent\ObjekWisataRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

class ObjekWisataController extends Controller
{
    protected $objekWisataRepo;

    public function __construct(ObjekWisataRepository $objekWisataRepo)
    {
        $this->objekWisataRepo = $objekWisataRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'id_kecamatan']);
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $objekWisatas = $this->objekWisataRepo->paginate(10, $filters, $sortField, $sortDirection);
        $kecamatans = Kecamatan::all();

        return Inertia::render('Admin/ObjekWisata/Index', [
            'objekWisatas' => $objekWisatas,
            'kecamatans' => $kecamatans,
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
        return Inertia::render('Admin/ObjekWisata/Create', [
            'kecamatans' => Kecamatan::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'nama_objek' => 'required|string|max:100|unique:objek_wisatas',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'keterangan' => 'required|string',
            'jam_operasional' => 'nullable|string|max:100',
            'harga_tiket' => 'nullable|string|max:100',
            'harga_tiket_lokal' => 'nullable|numeric',
            'harga_tiket_domestik' => 'nullable|numeric',
            'harga_tiket_asing' => 'nullable|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'fotos' => 'required|array|min:1',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:10240', // 10MB
            'akses_transportasi' => 'nullable|array',
            'akses_transportasi.*' => 'string',
        ]);

        try {
            $objekWisata = $this->objekWisataRepo->create(collect($validated)->except('fotos')->toArray());

            // Handle File Uploads
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $file) {
                    $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.jpg';
                    $path = 'fotos/objek-wisata/' . $filename;

                    // Ensure directory exists
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('fotos/objek-wisata')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('fotos/objek-wisata');
                    }

                    // Resize and Save using Intervention Image v3
                    $image = Image::read($file);
                    $image->scale(width: 800);

                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $image->toJpeg());

                    $objekWisata->fotos()->create([
                        'path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            return redirect()->route('admin.objek-wisata.index')
                ->with('message', 'Objek Wisata berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Objek Wisata Store Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objekWisata = $this->objekWisataRepo->findWithDetails($id);

        return Inertia::render('Admin/ObjekWisata/Show', [
            'objekWisata' => $objekWisata,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objekWisata = $this->objekWisataRepo->findWithDetails($id);

        return Inertia::render('Admin/ObjekWisata/Edit', [
            'objekWisata' => $objekWisata,
            'kecamatans' => Kecamatan::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $objekWisata = $this->objekWisataRepo->find($id);

        \Illuminate\Support\Facades\Log::info('Update Payload:', $request->all());

        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'nama_objek' => 'required|string|max:100|unique:objek_wisatas,nama_objek,' . $id,
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'keterangan' => 'required|string',
            'jam_operasional' => 'nullable|string|max:100',
            'harga_tiket' => 'nullable|string|max:100',
            'harga_tiket_lokal' => 'nullable|numeric',
            'harga_tiket_domestik' => 'nullable|numeric',
            'harga_tiket_asing' => 'nullable|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:10240',
            'akses_transportasi' => 'nullable|array',
            'akses_transportasi.*' => 'string',
        ]);

        $this->objekWisataRepo->update($id, collect($validated)->except('new_fotos')->toArray());

        // Handle New File Uploads
        if ($request->hasFile('new_fotos')) {
            foreach ($request->file('new_fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/objek-wisata/' . $filename;

                // Ensure directory exists
                if (!Storage::disk('public')->exists('fotos/objek-wisata')) {
                    Storage::disk('public')->makeDirectory('fotos/objek-wisata');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $objekWisata->fotos()->create([
                    'path' => $path,
                    'is_primary' => false, // Set manual primary later if needed
                ]);
            }
        }

        return redirect()->route('admin.objek-wisata.index')
            ->with('message', 'Objek Wisata berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $objekWisata = $this->objekWisataRepo->find($id);

        // Delete physical photos
        foreach ($objekWisata->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
            $foto->delete();
        }

        $this->objekWisataRepo->delete($id);

        Cache::forget('public.map.data');
        Cache::forget('public.home.data');

        return redirect()->route('admin.objek-wisata.index')
            ->with('message', 'Objek Wisata berhasil dihapus.');
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
        Cache::forget('public.home.data');

        return back()->with('message', 'Foto berhasil dihapus.');
    }

    /**
     * Set a photo as primary.
     */
    public function setPrimaryPhoto(string $objekId, string $fotoId)
    {
        $objekWisata = ObjekWisata::findOrFail($objekId);

        $objekWisata->fotos()->update(['is_primary' => false]);
        $foto = Foto::findOrFail($fotoId);
        $foto->update(['is_primary' => true]);

        Cache::forget('public.map.data');
        Cache::forget('public.home.data');

        return back()->with('message', 'Foto utama berhasil diupdate.');
    }
}
