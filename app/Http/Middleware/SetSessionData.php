<?php

namespace App\Http\Middleware;

use App\Models\BusinessSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetSessionData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('user')) {

            $user = Auth::user();

            $session_data = $user;

            $business = new BusinessSetting;

            $copy_right_text = @$business->where('type', 'copy_right_text')->first()->value;
            $business_logo = @$business->where('type', 'web_header_logo')->first()->value;
            $company_name = @$business->where('type', 'company_name')->first()->value;

            $request->session()->put('copy_right_text', $copy_right_text);
            $request->session()->put('business_logo', $business_logo);
            $request->session()->put('company_name', $company_name);
            // $request->session()->put('currency', $currency_data);

        }

        return $next($request);
    }
}
