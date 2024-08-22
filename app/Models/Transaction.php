<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price_each_date' => 'array',
        'guest_info' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancel')->where('deleted_at', null);
    }

    public function scopeInRange($query,$start,$end){
        $query->where('checkin_date','<=',$end)->where('checkout_date','>',$start);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function ratePlan()
    {
        return $this->belongsTo(RatePlan::class, 'booking_package_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }
}
