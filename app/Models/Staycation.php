<?php

namespace App\Models;

use App\helpers\AppHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Staycation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function amenitys()
    {
        return $this->hasMany(HomeStayAmenity::class);
    }

    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations[2]->value ?? $title;
    }

    // public function setAmenitiesAttribute($value)
    // {
    //     $this->attributes['amenities'] = json_encode($value);
    // }

    // // Accessor to decode the amenities attribute from JSON
    // public function getAmenitiesAttribute($value)
    // {
    //     return json_decode($value, true);
    // }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', AppHelper::default_lang());
                }
            }]);
        });
    }
}
