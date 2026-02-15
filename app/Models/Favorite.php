<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objek_wisata_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'objek_wisata_id');
    }
}
