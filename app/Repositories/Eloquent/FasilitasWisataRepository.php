<?php

namespace App\Repositories\Eloquent;

use App\Models\FasilitasWisata;
use App\Repositories\Contracts\BaseRepositoryInterface;

class FasilitasWisataRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(FasilitasWisata $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated facilities with relationships.
     */
    public function paginate($perPage = 10, array $filters = [])
    {
        $query = $this->model->with('objekWisata');

        if (isset($filters['search'])) {
            $query->where('nama_fasilitas', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['id_objek'])) {
            $query->where('id_objek', $filters['id_objek']);
        }

        return $query->latest()->paginate($perPage);
    }
}
