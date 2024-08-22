<?php

namespace App\helpers;

use App\Models\Notification;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class GlobalFunction
{
    public static function periodDate($startDate,$endDate,$day = true,$interval='1 day'){
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        if($day){
            $end = $end->modify('+1 day');
        }
        $interval = \DateInterval::createFromDateString($interval);
        $period = new \DatePeriod($begin, $interval, $end);
        return $period;
    }

    public static function storeNotification($type, $id)
    {
        $notification                            = new Notification;
        $notification->notificationable_type     = $type;
        $notification->notificationable_id       = $id;
        $notification->save();
    }

    public static function countNotification($model)
    {
        // dd($model);
        // $notification_count = 0;
        // if ($model == 'Transaction') {
        //     // $notification_count = Notification::commentable()
        // }

        $notification_count = Notification::where('is_seen', 0)
                                ->when(($model == 'Transaction'), function ($query) {
                                    return $query->where('notificationable_type', 'App\Models\Transaction');
                                })
                                ->when(($model == 'ContactUs'), function ($query) {
                                    return $query->where('notificationable_type', 'App\Models\ContactUs');
                                })
                                ->when(($model == 'Comment'), function ($query) {
                                    return $query->where('notificationable_type', 'App\Models\Comment');
                                })
                                ->count();
        return $notification_count;
    }
    public static function seenNotification($model)
    {
        Notification::where('is_seen', 0)
            ->where('notificationable_type', $model)
            ->update(['is_seen' => 1]);

    }
}
