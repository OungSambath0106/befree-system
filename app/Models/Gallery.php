<?php

namespace App\Models;

use App\Models\User;
use App\helpers\AppHelper;
use App\Models\Translation;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'category_id' => 'array',
    ];    

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'galleries';
    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'gallery_categories', 'gallery_id', 'category_id');
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $description;
        }
        return $this->translations[0]->value ?? $description;
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
