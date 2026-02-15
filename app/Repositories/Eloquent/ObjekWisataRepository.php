<?php

namespace App\Repositories\Eloquent;

use App\Models\ObjekWisata;
use App\Repositories\Contracts\BaseRepositoryInterface;

class ObjekWisataRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(ObjekWisata $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated objek wisatas with relationships.
     */
    public function paginate($perPage = 10, array $filters = [], $sortField = 'created_at', $sortDirection = 'desc')
    {
        $query = $this->model->with(['kecamatan', 'primaryFoto']);

        if (isset($filters['search'])) {
            $query->where('nama_objek', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['id_kecamatan']) && $filters['id_kecamatan'] !== '') {
            $query->where('id_kecamatan', $filters['id_kecamatan']);
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
    }

    /**
     * Find with all relationships.
     */
    public function findWithDetails($id)
    {
        return $this->model->with(['kecamatan', 'fotos', 'fasilitas', 'events'])->findOrFail($id);
    }
}
