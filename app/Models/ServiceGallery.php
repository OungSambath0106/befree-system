<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGallery extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'image' => 'array'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
