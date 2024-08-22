<?php

namespace App\Http\Controllers\Frontends;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\helpers\ImageManager;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CustomerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer', ['except' => ['loginForm', 'login', 'registerForm', 'register']]);
    }
    public function loginForm()
    {
         return view('frontends.users.login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            $output = [
                'success' => 0,
                'msg' => $firstError,
            ];
            if(request()->ajax()){
            return response()->json($output);
            }

            return redirect()->back()->with($output);
        }
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {
            $request->session()->regenerate();
            $output = [
                'success' => 1,
                'msg' => __('Login successfully')
            ];
        } else {
            $output = [
                'success' => 0,
                'msg' => __('Incorrect email or password')
            ];
        }
        if(request()->ajax()){

            return response()->json($output);
        }

        return redirect()->back()->with($output);
    }
    public function registerForm()
    {
        return view('frontends.users.register');
    }
    public function register(Request $request)
    {
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers', 'email')->where(function ($query) {
                        $query->whereNull('deleted_at');
                    }),
                ],
                'phone' => 'required',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);
            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                $output = [
                    'success' => 0,
                    'msg' => $firstError,
                ];
                if(request()->ajax()){
                    return response()->json($output);
                }

                return redirect()->back()->with($output);
            }
            $customer = new Customer();
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->email = $request->email;
            $customer->phone = $request->full_mobile;
            $customer->password = bcrypt($request->password);
            $customer->save();
            DB::commit();
            Auth::guard('customer')->login($customer);
            $output = [
                'success' => 1,
                'msg' => __('Register successfully')
            ];

        }catch (Exception $e){

            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' =>__('Something went wrong')
            ];
        }
        if(request()->ajax()){
            return response()->json($output);
        }

        return redirect()->back()->with($output);

    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $output = [
            'success' => 1,
            'msg' => __('Logout successfully')
        ];

        return response()->json($output);
    }

    public function profile()
    {
        return view('frontends.users.profile');
    }

    public function profileUpdate(Request $request)
    {

        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers', 'email')->where(function ($query) {
                        $query->whereNull('deleted_at')
                              ->where('id', '!=', Auth::guard('customer')->user()->id);
                    }),
                ],
                'password' => 'nullable|min:8',
                'confirm_password' => 'nullable|same:password',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                $output = [
                    'success' => 0,
                    'msg' => $firstError,
                ];
                return redirect()->back()->with($output);
            }
            $id = Auth::guard('customer')->user()->id;
            $customer = Customer::findOrFail($id);
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->phone = $request->full_mobile;
            $customer->password = $request->password ? bcrypt($request->password) : $customer->password;
            if ($request->hasFile('image')) {
                $customer->image = ImageManager::update('uploads/customers/', $customer->image, $request->image);
            }
            $customer->save();
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('Update successfully')
            ];
        }catch (Exception $e){
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' =>__('Something went wrong')
            ];
        }
        return redirect()->back()->with($output);
    }
}
