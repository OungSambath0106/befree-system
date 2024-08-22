<?php

namespace App\Models;

use App\Models\User;
use App\Models\BlogTag;
use App\Models\Comment;
use App\helpers\AppHelper;
use App\Models\Translation;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'blogs';
    protected $casts = [
            'tage' => 'array'
    ];

    protected $fillable = [
        'title',
        'description',
        'tage',
        'category_id',
    ];
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
    public function tages()
    {
        return $this->belongsToMany(BlogTag::class,'tage');
    }
    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations[0]->value ?? $title;
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $description;
        }
        return $this->translations[1]->value ?? $description;
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
