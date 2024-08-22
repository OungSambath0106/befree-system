<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\helpers\AppHelper;
use App\Models\Translation;
use App\helpers\GlobalFunction;
use App\Models\HomeStayAmenity;
use App\Models\HomeStayGallery;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    // protected $fillable = ['home_stay_room_id', 'title', 'description', 'amenities', 'checkin', 'checkout', 'created_by'];
    protected $casts = [
        'amenities' => 'array',
        'checkin' => 'array',
        'checkout' => 'array',
    ];

    public function createdBy()
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
    public function homestaygaller()
    {
        return $this->hasMany(HomeStayGallery::class, 'home_stay_id');
    }

    public function RatePlan()
    {
        return $this->hasMany(RatePlan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'room_id');
    }

    public function getCheckInAttribute($check_in)
    {
        if (strpos(url()->current(), '/admin')) {
            return $check_in;
        }
        return $this->translations[0]->value ?? $check_in;
    }
    public function getCheckOutAttribute($check_out)
    {
        if (strpos(url()->current(), '/admin')) {
            return $check_out;
        }
        return $this->translations[1]->value ?? $check_out;
    }
    public function getTitleAttribute($title)
    {
        if (strpos(url()->current(), '/admin')) {
            return $title;
        }
        return $this->translations[2]->value ?? $title;
    }
    public function getSpecialNoteAttribute($special_note)
    {
        if (strpos(url()->current(), '/admin')) {
            return $special_note;
        }
        return $this->translations[3]->value ?? $special_note;
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $description;
        }
        return $this->translations[4]->value ?? $description;
    }
    public function getColumn1Attribute($column1)
    {
        if (strpos(url()->current(), '/admin')) {
            return $column1;
        }
        return $this->translations[5]->value ?? $column1;
    }
    public function getColumn2Attribute($column2)
    {
        if (strpos(url()->current(), '/admin')) {
            return $column2;
        }
        return $this->translations[6]->value ?? $column2;
    }
    public function getColumn3Attribute($column3)
    {
        if (strpos(url()->current(), '/admin')) {
            return $column3;
        }
        return $this->translations[7]->value ?? $column3;
    }
    public function getBookingsInRange($from, $to)
    {
        return Transaction::where('room_id', $this->id)
            ->active()
            ->inRange($from, $to)
            ->get();
    }
    public function roomDates()
    {
        return $this->hasMany(RoomDate::class);
    }

    public function getDatesInRange($start_date, $end_date)
    {
        // dd($start_date, $end_date);
        $query = RoomDate::query();
        $query->where('room_id', $this->id);
        // dd($query->pluck('start_date'));
        $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($start_date)));
        $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($end_date)));

        return $query->take(100)->get();
    }

    public function isAvailableAt($filters = [])
    {
        // dd($filters);
        if (empty($filters['start_date']) or empty($filters['end_date'])) return true;

        $start_date = Carbon::createFromFormat('d/m/Y', $filters['start_date'])->format('Y-m-d');
        $end_date = Carbon::createFromFormat('d/m/Y', $filters['end_date'])->format('Y-m-d');

        $filters['end_date'] = date("Y-m-d", strtotime($end_date . " -1day"));
        // dd($filters);

        $roomDates =  $this->getDatesInRange($start_date, $end_date);
        // dd($roomDates);
        $allDates = [];
        $tmp_price = 0;
        $tmp_night = 0;
        $period = GlobalFunction::periodDate($start_date, $end_date, true);
        // dd($period);
        foreach ($period as $dt) {
            $allDates[$dt->format('Y-m-d')] = [
                'number' => $this->number,
                // 'price'=>$this->price
            ];
            $tmp_night++;
        }
        // dd($allDates);
        if ($roomDates->count() > 0 && !empty($roomDates)) {
            foreach ($roomDates as $row) {
                // dd($row);
                if (!$row->is_active or !$row->number) return false;

                if (!array_key_exists(date('Y-m-d', strtotime($row->start_date)), $allDates)) continue;

                $allDates[date('Y-m-d', strtotime($row->start_date))] = [
                    'number' => $row->number
                ];
            }
        }

        $roomBookings = $this->getBookingsInRange($start_date, $end_date);
        // dd($roomBookings);

        if ($roomBookings->count() > 0 && !empty($roomBookings)) {
            // dd(1);
            foreach ($roomBookings as $roomBooking) {
                $period = GlobalFunction::periodDate($roomBooking->checkin_date, $roomBooking->checkout_date, false);
                foreach ($period as $dt) {
                    $date = $dt->format('Y-m-d');
                    if (!array_key_exists($date, $allDates)) continue;
                    $allDates[$date]['number'] -= 1;
                    if ($allDates[$date]['number'] <= 0) {
                        return false;
                    }
                }
            }
            // dd($allDates);
        }

        // dd($filters);
        $this->tmp_number = !empty($allDates) ?  (int) min(array_column($allDates, 'number')) : 0;
        if (empty($this->tmp_number)) return false;
        // dd($this->tmp_number);
        if ($this->tmp_number < $filters['number'])
            return false;

        //Adult - Children
        if (!empty($filters['adult']) and $this->adult * $this->tmp_number < $filters['adult']) {
            return false;
        }
        if (!empty($filters['child']) and $this->child * $this->tmp_number < $filters['child']) {
            return false;
        }

        $this->tmp_price = array_sum(array_column($allDates, 'price'));
        $this->tmp_dates = $allDates;
        $this->tmp_nights = $tmp_night;

        return true;
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
