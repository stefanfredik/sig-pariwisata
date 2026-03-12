<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\ObjekWisata;
use App\Models\Foto;
use App\Repositories\Eloquent\ObjekWisataRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
        $query = ObjekWisata::query()->with(['kecamatan', 'fotos']);

        if ($request->filled('search')) {
            $query->where('nama_objek', 'like', '%' . $request->search . '%')
                ->orWhere('alamat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        $objekWisatas = $query->latest()->paginate(10)->withQueryString();
        $kecamatans = Kecamatan::all();

        return Inertia::render('Admin/ObjekWisata/Index', [
            'objekWisatas' => $objekWisatas,
            'kecamatans' => $kecamatans,
            'filters' => $request->only(['search', 'kecamatan']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = Kecamatan::all();
        return Inertia::render('Admin/ObjekWisata/Create', [
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'nama_objek' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:objek_wisatas,slug',
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'daya_tarik_utama' => 'nullable|string',
            'alamat' => 'required|string|max:255',
            'harga_tiket' => 'nullable|string',
            'harga_tiket_lokal' => 'nullable|numeric',
            'harga_tiket_domestik' => 'nullable|numeric',
            'harga_tiket_asing' => 'nullable|numeric',
            'harga_parkir_motor' => 'nullable|numeric',
            'harga_parkir_mobil' => 'nullable|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'fotos' => 'required|array|min:1',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600', // 25MB
            'akses_transportasi' => 'nullable|array',
            'akses_transportasi.*' => 'string',
        ]);

        try {
            $objekWisata = $this->objekWisataRepo->create(collect($validated)->except('fotos')->toArray());

            // Handle File Uploads
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $file) {
                    $filename = time() . '_' . Str::random(10) . '.jpg';
                    $path = 'fotos/objek-wisata/' . $filename;

                    // Ensure directory exists
                    if (!Storage::disk('public')->exists('fotos/objek-wisata')) {
                        Storage::disk('public')->makeDirectory('fotos/objek-wisata');
                    }

                    // Resize and Save
                    $image = Image::read($file);
                    $image->scale(width: 800);
                    Storage::disk('public')->put($path, (string) $image->toJpeg());

                    $objekWisata->fotos()->create([
                        'path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            Cache::forget('public.map.data');
            Cache::forget('public.home.data');

            return redirect()->route('admin.objek-wisata.index')
                ->with('message', 'Objek Wisata berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objekWisata = ObjekWisata::with(['kecamatan', 'fotos', 'fasilitas', 'events'])->findOrFail($id);
        return Inertia::render('Admin/ObjekWisata/Show', [
            'objekWisata' => $objekWisata,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objekWisata = ObjekWisata::with('fotos')->findOrFail($id);
        $kecamatans = Kecamatan::all();

        return Inertia::render('Admin/ObjekWisata/Edit', [
            'objekWisata' => $objekWisata,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\Log::info('Update Objek Wisata called for ID: ' . $id);
        \Illuminate\Support\Facades\Log::info('Payload: ', $request->all());

        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:kecamatans,id',
            'nama_objek' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:objek_wisatas,slug,' . $id,
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'daya_tarik_utama' => 'nullable|string',
            'alamat' => 'required|string|max:255',
            'harga_tiket' => 'nullable|string',
            'harga_tiket_lokal' => 'nullable|numeric',
            'harga_tiket_domestik' => 'nullable|numeric',
            'harga_tiket_asing' => 'nullable|numeric',
            'harga_parkir_motor' => 'nullable|numeric',
            'harga_parkir_mobil' => 'nullable|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'nullable', // Can be empty string from frontend if no file selected but field present
            'akses_transportasi' => 'nullable|array',
            'akses_transportasi.*' => 'string',
        ]);

        try {
            $this->objekWisataRepo->update($id, collect($validated)->except('new_fotos')->toArray());

            // Handle New File Uploads
            if ($request->hasFile('new_fotos')) {
                $objekWisata = ObjekWisata::findOrFail($id);
                foreach ($request->file('new_fotos') as $file) {
                    if (!$file) continue;
                    
                    $filename = time() . '_' . Str::random(10) . '.jpg';
                    $path = 'fotos/objek-wisata/' . $filename;

                    // Ensure directory exists
                    if (!Storage::disk('public')->exists('fotos/objek-wisata')) {
                        Storage::disk('public')->makeDirectory('fotos/objek-wisata');
                    }

                    // Resize and Save
                    $image = Image::read($file);
                    $image->scale(width: 800);
                    Storage::disk('public')->put($path, (string) $image->toJpeg());

                    $objekWisata->fotos()->create([
                        'path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            Cache::forget('public.map.data');
            Cache::forget('public.home.data');

            return redirect()->route('admin.objek-wisata.index')
                ->with('message', 'Objek Wisata berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating Objek Wisata: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $objekWisata = ObjekWisata::with('fotos')->findOrFail($id);
        
        // Delete associated photos from storage
        foreach ($objekWisata->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $this->objekWisataRepo->delete($id);

        Cache::forget('public.map.data');
        Cache::forget('public.home.data');

        return redirect()->route('admin.objek-wisata.index')
            ->with('message', 'Objek Wisata berhasil dihapus.');
    }

    /**
     * Add photos directly to an existing object.
     */
    public function addPhoto(Request $request, $id)
    {
        $request->validate([
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        $objek = ObjekWisata::findOrFail($id);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/objek-wisata/' . $filename;

                // Ensure directory exists
                if (!Storage::disk('public')->exists('fotos/objek-wisata')) {
                    Storage::disk('public')->makeDirectory('fotos/objek-wisata');
                }

                // Resize and Save
                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $objek->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.map.data');
        Cache::forget('public.home.data');

        return back()->with('message', 'Foto berhasil ditambahkan.');
    }

    /**
     * Delete a specific photo.
     */
    public function deletePhoto($id)
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
