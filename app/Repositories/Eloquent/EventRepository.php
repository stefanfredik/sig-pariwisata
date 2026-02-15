<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Repositories\Contracts\BaseRepositoryInterface;

class EventRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated events with relationships.
     */
    public function paginate($perPage = 10, array $filters = [], $sortField = 'tanggal_mulai', $sortDirection = 'desc')
    {
        $query = $this->model->with(['objekWisata', 'fotos']);

        if (isset($filters['search'])) {
            $query->where('nama_event', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['id_objek'])) {
            $query->where('id_objek', $filters['id_objek']);
        }

        if (isset($filters['status'])) {
            switch ($filters['status']) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'ongoing':
                    $query->ongoing();
                    break;
                case 'past':
                    $query->past();
                    break;
            }
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
    }

    /**
     * Find with all relationships.
     */
    public function findWithDetails($id)
    {
        return $this->model->with(['objekWisata', 'fotos'])->findOrFail($id);
    }
}
