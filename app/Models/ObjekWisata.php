<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ObjekWisata extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_kecamatan',
        'nama_objek',
        'slug',
        'alamat',
        'no_telepon',
        'keterangan',
        'daya_tarik_utama',
        'jam_operasional',
        'harga_tiket',
        'harga_tiket_lokal',
        'harga_tiket_domestik',
        'harga_tiket_asing',
        'harga_parkir_motor',
        'harga_parkir_mobil',
        'latitude',
        'longitude',
        'rating_avg',
        'review_count',
        'view_count',
        'akses_transportasi',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating_avg' => 'decimal:2',
        'akses_transportasi' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from nama_objek
        static::creating(function ($objekWisata) {
            if (empty($objekWisata->slug)) {
                $objekWisata->slug = Str::slug($objekWisata->nama_objek);
            }
        });

        static::updating(function ($objekWisata) {
            if ($objekWisata->isDirty('nama_objek') && empty($objekWisata->slug)) {
                $objekWisata->slug = Str::slug($objekWisata->nama_objek);
            }
        });
    }

    /**
     * Get the kecamatan that owns the objek wisata.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    /**
     * Get the fotos for the objek wisata.
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

    /**
     * Get the reviews for the objek wisata.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_objek');
    }

    /**
     * Get approved reviews only.
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'id_objek')->where('status', 'approved');
    }

    /**
     * Get the fasilitas for the objek wisata.
     */
    public function fasilitas()
    {
        return $this->hasMany(FasilitasWisata::class, 'id_objek');
    }

    /**
     * Get the events for the objek wisata.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'id_objek');
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Recalculate rating average.
     */
    public function recalculateRating()
    {
        $approved = $this->approvedReviews();
        $this->rating_avg = $approved->avg('rating') ?? 0;
        $this->review_count = $approved->count();
        $this->save();
    }
}
