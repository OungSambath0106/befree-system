<?php

namespace App\Models;

use App\Models\User;
use App\helpers\AppHelper;
use App\Models\Translation;
use App\Models\ServiceGallery;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'services';
    protected $fillable = ['title', 'description', 'extra_info', 'created_by'];
    protected $casts = [
        'extra_info' => 'array'
    ];

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function ServiceGallery()
    {
        return $this->hasMany(ServiceGallery::class, 'service_id', 'id');
    }
    public function getExtraInfoAttribute($extra_info)
    {
        // if (strpos(url()->current(), '/admin')) {
        //     return json_decode($extra_info, true);
        //     // return $extra_info;
        // }
        // return $this->translations[3]->value ?? $extra_info;
        if (strpos(url()->current(), '/admin')) {

            if (!is_array($extra_info)) {
                $extra_info = json_decode($extra_info, true);
            }
            return $extra_info;
            // return $extra_info;
        }
        $value = $this->translations[0]->value ?? $extra_info;
        if (!is_array($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations[1]->value ?? $title;
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $description;
        }
        return $this->translations[2]->value ?? $description;
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
