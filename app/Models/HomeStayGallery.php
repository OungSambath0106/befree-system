<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeStayGallery extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'image' => 'array'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class,'home_stay_id');
    }
}
