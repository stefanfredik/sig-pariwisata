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
    public function paginate($perPage = 10, $sortField = 'created_at', $sortDirection = 'desc', $hasObjects = null)
    {
        $query = $this->model->withCount('objekWisatas');

        if ($hasObjects !== null) {
            if ($hasObjects === 'yes') {
                $query->has('objekWisatas');
            } elseif ($hasObjects === 'no') {
                $query->doesntHave('objekWisatas');
            }
        }

        return $query
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Search kecamatans by name.
     */
    public function search(string $query, $sortField = 'created_at', $sortDirection = 'desc', $hasObjects = null)
    {
        $q = $this->model->where('nama_kecamatan', 'like', "%{$query}%")
            ->withCount('objekWisatas');

        if ($hasObjects !== null) {
            if ($hasObjects === 'yes') {
                $q->has('objekWisatas');
            } elseif ($hasObjects === 'no') {
                $q->doesntHave('objekWisatas');
            }
        }

        return $q
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();
    }
}
