<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use App\helpers\AppHelper;

class Promotion extends Model
{
    use HasFactory;

    protected $appends = ['banner_url'];

    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
        'brands' => 'array',
    ];

    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations->where('key', 'title')->first()->value ?? $title;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product', 'promotion_id', 'product_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Product::class, 'promotion_brand', 'promotion_id', 'brand_id');
    }

    public function getBannerUrlAttribute()
    {
        if (!empty($this->banner)) {
            $image_url = asset('uploads/promotions/' . rawurlencode($this->banner));
        } else {
            $image_url = null;
        }
        return $image_url;
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

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
