<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function logout()
    {
        request()->session()->flush();
        \Auth::logout();
        return redirect('/login');
    }

    protected function redirectTo()
    {
        // $user = \Auth::user();
        // if (!$user->can('dashboard.data') && $user->can('sell.create')) {
        //     return '/pos/create';
        // }

        // if ($user->user_type == 'user_customer') {
        //     return 'contact/contact-dashboard';
        // }

        return '/admin/dashboard';
    }

    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
