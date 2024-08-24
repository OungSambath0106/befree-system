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

    protected $appends = ['header_banner_url', 'footer_banner_url'];

    protected $guarded = [];

    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations->where('key', 'title')->first()->value ?? $title;
    }

    public function getShortDescriptionAttribute($short_description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $short_description;
        }
        return $this->translations->where('key', 'short_description')->first()->value ?? $short_description;
    }

    public function getContentAttribute($content)
    {
        if (strpos(url()->current(), '/admin')) {
            return $content;
        }
        return $this->translations->where('key', 'content')->first()->value ?? $content;
    }

    public function getHeaderBannerUrlAttribute()
    {
        if (!empty($this->header_banner)) {
            $image_url = asset('uploads/promotions/' . rawurlencode($this->header_banner));
        } else {
            $image_url = null;
        }
        return $image_url;
    }

    public function getFooterBannerUrlAttribute()
    {
        if (!empty($this->footer_banner)) {
            $image_url = asset('uploads/promotions/' . rawurlencode($this->footer_banner));
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
