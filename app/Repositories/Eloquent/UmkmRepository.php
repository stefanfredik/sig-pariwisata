<?php

namespace App\Repositories\Eloquent;

use App\Models\Umkm;
use App\Repositories\Contracts\BaseRepositoryInterface;

class UmkmRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Umkm $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated UMKM with relationships.
     */
    public function paginate($perPage = 10, array $filters = [], $sortField = 'created_at', $sortDirection = 'desc')
    {
        $query = $this->model->with(['primaryFoto']);

        if (isset($filters['search']) && $filters['search'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_umkm', 'like', "%{$filters['search']}%")
                    ->orWhere('alamat', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['kategori']) && $filters['kategori'] !== '') {
            $query->where('kategori', $filters['kategori']);
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
    }

    /**
     * Find with all relationships.
     */
    public function findWithDetails($id)
    {
        return $this->model->with(['fotos'])->findOrFail($id);
    }
}
