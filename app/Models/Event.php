<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_objek',
        'nama_event',
        'slug',
        'tanggal_mulai',
        'tanggal_selesai',
        'alamat',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from nama_event
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->nama_event);
            }
        });
    }

    /**
     * Get the objek wisata that owns the event.
     */
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    /**
     * Get the fotos for the event.
     */
    public function fotos()
    {
        return $this->morphMany(Foto::class, 'fotoable');
    }

    /**
     * Scope for upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now());
    }

    /**
     * Scope for ongoing events.
     */
    public function scopeOngoing($query)
    {
        return $query->where('tanggal_mulai', '<=', now())
                     ->where('tanggal_selesai', '>=', now());
    }

    /**
     * Scope for past events.
     */
    public function scopePast($query)
    {
        return $query->where('tanggal_selesai', '<', now());
    }
}
