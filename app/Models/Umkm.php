<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_umkm',
        'slug',
        'kategori',
        'alamat',
        'latitude',
        'longitude',
        'keterangan',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($umkm) {
            if (empty($umkm->slug)) {
                $umkm->slug = Str::slug($umkm->nama_umkm);
            }
        });

        static::updating(function ($umkm) {
            if ($umkm->isDirty('nama_umkm') && empty($umkm->slug)) {
                $umkm->slug = Str::slug($umkm->nama_umkm);
            }
        });
    }

    /**
     * Get the fotos for the UMKM.
     */
    public function fotos()
    {
        return $this->morphMany(Foto::class, 'fotoable');
    }

    /**
     * Get the primary foto.
     */
    public function primaryFoto()
    {
        return $this->morphOne(Foto::class, 'fotoable')->where('is_primary', true);
    }
}
