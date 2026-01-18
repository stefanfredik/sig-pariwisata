<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_objek',
        'rating',
        'judul',
        'komentar',
        'status',
        'approved_by',
        'approved_at',
        'helpful_count',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Recalculate objek wisata rating when review is approved
        static::updated(function ($review) {
            if ($review->isDirty('status') && $review->status === 'approved') {
                $review->objekWisata->recalculateRating();
            }
        });

        // Recalculate rating when review is deleted
        static::deleted(function ($review) {
            if ($review->status === 'approved') {
                $review->objekWisata->recalculateRating();
            }
        });
    }

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the objek wisata that owns the review.
     */
    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek');
    }

    /**
     * Get the admin who approved the review.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the fotos for the review.
     */
    public function fotos()
    {
        return $this->morphMany(Foto::class, 'fotoable');
    }

    /**
     * Scope for pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected reviews.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approve the review.
     */
    public function approve($adminId)
    {
        $this->status = 'approved';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Reject the review.
     */
    public function reject($adminId)
    {
        $this->status = 'rejected';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        $this->save();
    }
}
