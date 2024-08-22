<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Event;

class SetFrontendSession
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
        $business = new BusinessSetting;

        $copy_right_text = @$business->where('type', 'copy_right_text')->first()->value;
        $web_header_logo = @$business->where('type', 'web_header_logo')->first()->value;
        $web_banner_logo = @$business->where('type', 'web_banner_logo')->first()->value;
        $company_name = @$business->where('type', 'company_name')->first()->value;
        $company_short_description = @$business->where('type', 'company_short_description')->first()->value;

        $request->session()->put('copy_right_text', $copy_right_text);
        $request->session()->put('web_header_logo', $web_header_logo);
        $request->session()->put('web_banner_logo', $web_banner_logo);
        $request->session()->put('company_name', $company_name);
        $request->session()->put('company_short_description', $company_short_description);

        return $next($request);
    }
}
