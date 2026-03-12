<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Foto;
use App\Repositories\Eloquent\UmkmRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class UmkmController extends Controller
{
    protected $umkmRepo;

    public function __construct(UmkmRepository $umkmRepo)
    {
        $this->umkmRepo = $umkmRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Umkm::query()->with('primaryFoto');

        // Filtering
        if ($request->filled('search')) {
            $query->where('nama_umkm', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        // Sorting
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $umkms = $query->paginate(10)->withQueryString();

        $categories = Umkm::distinct()->pluck('kategori')->filter()->values();

        return Inertia::render('Admin/Umkm/Index', [
            'umkms' => $umkms,
            'categories' => $categories,
            'filters' => $request->only(['search', 'kategori', 'sort_field', 'sort_direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Umkm/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:umkms,slug',
            'kategori' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'fotos' => 'required|array|min:1',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        try {
            $umkm = $this->umkmRepo->create(collect($validated)->except('fotos')->toArray());

            // Handle File Uploads
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $file) {
                    $filename = time() . '_' . Str::random(10) . '.jpg';
                    $path = 'fotos/umkm/' . $filename;

                    // Ensure directory exists
                    if (!Storage::disk('public')->exists('fotos/umkm')) {
                        Storage::disk('public')->makeDirectory('fotos/umkm');
                    }

                    $image = Image::read($file);
                    $image->scale(width: 800);
                    Storage::disk('public')->put($path, (string) $image->toJpeg());

                    $umkm->fotos()->create([
                        'path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            Cache::forget('public.map.data');

            return redirect()->route('admin.umkm.index')
                ->with('message', 'UMKM berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $umkm = Umkm::with('fotos')->findOrFail($id);
        return Inertia::render('Admin/Umkm/Show', [
            'umkm' => $umkm
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $umkm = Umkm::with('fotos')->findOrFail($id);
        return Inertia::render('Admin/Umkm/Edit', [
            'umkm' => $umkm
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:umkms,slug,' . $id,
            'kategori' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'new_fotos' => 'nullable|array',
            'new_fotos.*' => 'image|mimes:jpg,jpeg,png,webp,heic,heif|max:25600',
        ]);

        $this->umkmRepo->update($id, collect($validated)->except('new_fotos')->toArray());

        // Handle New File Uploads
        if ($request->hasFile('new_fotos')) {
            $umkm = Umkm::findOrFail($id);
            foreach ($request->file('new_fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/umkm/' . $filename;

                // Ensure directory exists
                if (!Storage::disk('public')->exists('fotos/umkm')) {
                    Storage::disk('public')->makeDirectory('fotos/umkm');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $umkm->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.map.data');

        return redirect()->route('admin.umkm.index')
            ->with('message', 'UMKM berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $umkm = Umkm::findOrFail($id);
        
        foreach ($umkm->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }

        $this->umkmRepo->delete($id);

        Cache::forget('public.map.data');

        return redirect()->route('admin.umkm.index')
            ->with('message', 'UMKM berhasil dihapus.');
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

        $umkm = Umkm::findOrFail($id);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . Str::random(10) . '.jpg';
                $path = 'fotos/umkm/' . $filename;

                if (!Storage::disk('public')->exists('fotos/umkm')) {
                    Storage::disk('public')->makeDirectory('fotos/umkm');
                }

                $image = Image::read($file);
                $image->scale(width: 800);
                Storage::disk('public')->put($path, (string) $image->toJpeg());

                $umkm->fotos()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('public.map.data');

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

        Cache::forget('public.map.data');

        return back()->with('message', 'Foto berhasil dihapus.');
    }

    /**
     * Set primary photo.
     */
    public function setPrimaryPhoto(string $umkmId, string $fotoId)
    {
        $umkm = Umkm::findOrFail($umkmId);
        $umkm->fotos()->update(['is_primary' => false]);
        
        $foto = Foto::findOrFail($fotoId);
        $foto->update(['is_primary' => true]);

        Cache::forget('public.map.data');

        return back()->with('message', 'Foto utama berhasil diupdate.');
    }
}
