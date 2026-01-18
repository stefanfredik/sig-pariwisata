<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $fillable = [
        'fotoable_id',
        'fotoable_type',
        'path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the parent fotoable model (ObjekWisata, Event, or Review).
     */
    public function fotoable()
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL of the foto.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
