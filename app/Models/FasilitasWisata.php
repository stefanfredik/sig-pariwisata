<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasWisata extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_objek',
        'nama_fasilitas',
        'kategori_fasilitas',
        'deskripsi',
        'icon',
    ];

    protected $casts = [
    ];

    /**
     * Get the objek wisata that owns the fasilitas.
     */
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    /**
     * Get the fotos for the fasilitas.
     */
    public function fotos()
    {
        return $this->morphMany(Foto::class, 'fotoable');
    }
}
