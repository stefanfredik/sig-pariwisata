<?php

namespace App\Repositories\Eloquent;

use App\Models\Kecamatan;
use App\Repositories\Contracts\BaseRepositoryInterface;

class KecamatanRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Kecamatan $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated kecamatans.
     */
    public function paginate($perPage = 10)
    {
        return $this->model->withCount('objekWisatas')->paginate($perPage);
    }

    /**
     * Search kecamatans by name.
     */
    public function search(string $query)
    {
        return $this->model
            ->where('nama_kecamatan', 'like', "%{$query}%")
            ->withCount('objekWisatas')
            ->paginate(10)
            ->withQueryString();
    }
}
