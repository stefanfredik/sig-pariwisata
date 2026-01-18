<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kecamatan',
    ];

    /**
     * Get the objek wisatas for the kecamatan.
     */
    public function objekWisatas()
    {
        return $this->hasMany(ObjekWisata::class, 'id_kecamatan');
    }
}
