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

class ExtraService extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'extra_services';
    protected $fillable = ['title', 'description', 'created_by'];
    protected $casts = [
        'description' => 'array'
    ];

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            
            if (!is_array($description)) {
                $description = json_decode($description, true);
            }
            return $description;
            // return $description;
        }
        $value = $this->translations[0]->value ?? $description;
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
