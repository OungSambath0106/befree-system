<?php

namespace App\helpers;

use App\User;
use Carbon\Carbon;
use App\Model\Admin;
use App\Model\Color;
use App\Model\Order;
use App\Model\Coupon;
use App\Model\Review;
use App\Model\Seller;
use App\Models\Event;
use App\Model\Category;
use App\Model\Currency;
use App\Models\MenuCategory;
use App\Model\ShippingMethod;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class AppHelper
{

    public static function default_lang()
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('locale')) {
            $lang = session('locale');
        } else {
            $data = AppHelper::get_business_settings('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            app()->setLocale($code);
            session()->put('locale', $code);
            // session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }
        return $lang;
    }

    public static function get_language_name($key)
    {
        $values = AppHelper::get_business_settings('language');
        foreach ($values as $value) {
            if ($value['code'] == $key) {
                $key = $value['name'];
            }
        }

        return $key;
    }


    public static function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }

        return $config;
    }

    public static function error_processor ($validator) {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }

    public static function get_menu()
    {
        $menus = MenuCategory::withoutGlobalScopes()->with('translations')
                            ->where('status', 1)
                            ->where('parent_id', null)
                            ->orWhere('parent_id', 0)
                            ->get();

        return $menus;
    }

    public static function get_sub_menu($parent_id)
    {
        $sub_menus = MenuCategory::withoutGlobalScopes()->with('translations')
                                ->where('status', 1)
                                ->where('parent_id', $parent_id)
                                ->get();

        return $sub_menus;
    }

    public static function get_product()
    {
        $products = Event::withoutGlobalScopes()->with('translations')
                            ->get();
        return $products;
    }

    public static function getEventYears()
    {
        $event_years = Event::latest('start_date')->get()
                        ->map(function ($query) {
                            return Carbon::parse($query->start_date)->year;
                        });

        return $event_years;
    }
}


