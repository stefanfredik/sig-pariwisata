<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Repositories\Eloquent\KecamatanRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KecamatanController extends Controller
{
    protected $kecamatanRepo;

    public function __construct(KecamatanRepository $kecamatanRepo)
    {
        $this->kecamatanRepo = $kecamatanRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $hasObjects = $request->get('has_objects');

        if ($search) {
            $kecamatans = $this->kecamatanRepo->search($search, $sortField, $sortDirection, $hasObjects);
        } else {
            $kecamatans = $this->kecamatanRepo->paginate(10, $sortField, $sortDirection, $hasObjects);
        }

        return Inertia::render('Admin/Kecamatan/Index', [
            'kecamatans' => $kecamatans,
            'filters' => [
                'search' => $search,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
                'has_objects' => $hasObjects,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Kecamatan/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:50|unique:kecamatans',
        ], [
            'nama_kecamatan.required' => 'Nama kecamatan harus diisi.',
            'nama_kecamatan.unique' => 'Nama kecamatan sudah ada.',
            'nama_kecamatan.max' => 'Nama kecamatan maksimal 50 karakter.',
        ]);

        $this->kecamatanRepo->create($validated);

        return redirect()->route('admin.kecamatan.index')
            ->with('message', 'Kecamatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kecamatan = $this->kecamatanRepo->find($id);
        $kecamatan->load('objekWisatas');

        return Inertia::render('Admin/Kecamatan/Show', [
            'kecamatan' => $kecamatan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kecamatan = $this->kecamatanRepo->find($id);

        return Inertia::render('Admin/Kecamatan/Edit', [
            'kecamatan' => $kecamatan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:50|unique:kecamatans,nama_kecamatan,' . $id,
        ], [
            'nama_kecamatan.required' => 'Nama kecamatan harus diisi.',
            'nama_kecamatan.unique' => 'Nama kecamatan sudah ada.',
            'nama_kecamatan.max' => 'Nama kecamatan maksimal 50 karakter.',
        ]);

        $this->kecamatanRepo->update($id, $validated);

        return redirect()->route('admin.kecamatan.index')
            ->with('message', 'Kecamatan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->kecamatanRepo->delete($id);

        return redirect()->route('admin.kecamatan.index')
            ->with('message', 'Kecamatan berhasil dihapus.');
    }
}
