<?php

namespace App\Models;

use App\Models\Page;
use App\helpers\AppHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionTitle extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations[0]->value ?? $title;
    }
    public function getDefaultTitleAttribute($default_title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $default_title;
        }
        return $this->translations[1]->value ?? $default_title;
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
