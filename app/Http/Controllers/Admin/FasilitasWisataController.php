<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObjekWisata;
use App\Models\FasilitasWisata;
use App\Repositories\Eloquent\FasilitasWisataRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $fasilitas = $this->fasilitasRepo->paginate(10, $filters);
        $objekWisatas = ObjekWisata::all();

        return Inertia::render('Admin/FasilitasWisata/Index', [
            'fasilitas' => $fasilitas,
            'objekWisatas' => $objekWisatas,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/FasilitasWisata/Create', [
            'objekWisatas' => ObjekWisata::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_objek' => 'required|exists:objek_wisatas,id',
            'nama_fasilitas' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'icon' => 'nullable|string|max:50',
        ]);

        $this->fasilitasRepo->create($validated);

        return redirect()->route('admin.fasilitas-wisata.index')
            ->with('message', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fasilitas = $this->fasilitasRepo->find($id);

        return Inertia::render('Admin/FasilitasWisata/Edit', [
            'fasilitas' => $fasilitas,
            'objekWisatas' => ObjekWisata::all(),
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
            'deskripsi' => 'nullable|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'icon' => 'nullable|string|max:50',
        ]);

        $this->fasilitasRepo->update($id, $validated);

        return redirect()->route('admin.fasilitas-wisata.index')
            ->with('message', 'Fasilitas berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->fasilitasRepo->delete($id);

        return redirect()->route('admin.fasilitas-wisata.index')
            ->with('message', 'Fasilitas berhasil dihapus.');
    }
}
