<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Foto;
use App\Repositories\Eloquent\UmkmRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

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
        $filters = $request->only(['search', 'kategori']);
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $umkms = $this->umkmRepo->paginate(10, $filters, $sortField, $sortDirection);
        $categories = ['Warung makan', 'Kios', 'Villa', 'Hotel', 'Penginapan'];

        return Inertia::render('Admin/Umkm/Index', [
            'umkms' => $umkms,
            'categories' => $categories,
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
        return Inertia::render('Admin/Umkm/Create', [
            'categories' => ['Warung makan', 'Kios', 'Villa', 'Hotel', 'Penginapan'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:100|unique:umkms',
            'kategori' => 'required|in:Warung makan,Kios,Villa,Hotel,Penginapan',
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

                    // Resize and Save using Intervention Image v3
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
            \Illuminate\Support\Facades\Log::error('UMKM Store Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $umkm = $this->umkmRepo->findWithDetails($id);

        return Inertia::render('Admin/Umkm/Show', [
            'umkm' => $umkm,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $umkm = $this->umkmRepo->findWithDetails($id);

        return Inertia::render('Admin/Umkm/Edit', [
            'umkm' => $umkm,
            'categories' => ['Warung makan', 'Kios', 'Villa', 'Hotel', 'Penginapan'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $umkm = $this->umkmRepo->find($id);

        $validated = $request->validate([
            'nama_umkm' => 'required|string|max:100|unique:umkms,nama_umkm,' . $id,
            'kategori' => 'required|in:Warung makan,Kios,Villa,Hotel,Penginapan',
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
            ->with('message', 'UMKM berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $umkm = $this->umkmRepo->find($id);

        // Delete physical photos
        foreach ($umkm->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
            $foto->delete();
        }

        $this->umkmRepo->delete($id);

        Cache::forget('public.map.data');

        return redirect()->route('admin.umkm.index')
            ->with('message', 'UMKM berhasil dihapus.');
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

    /**
     * Set a photo as primary.
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
