<?php

namespace App\Http\Controllers\Frontends;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;

class CustomerResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function resetPasswordForm($token)
    {
        return view('frontends.users.reset-password', ['token' => $token]);
    }

    public function resetPasswordSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            $output = [
                'success' => 0,
                'msg' => $firstError,
            ];
        
            return redirect()->back()->with($output);
        }
        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();
        if($updatePassword){
            $customer = Customer::where('email', $updatePassword->email)
                ->update(['password' => bcrypt($request->password)]);
                DB::table('password_resets')->where(['token' => $request->token])->delete();
            $output = [
                'success' => 1,
                'msg' => __('Password reset successfully'),
                'showLoginModal' => true,
            ];
            return response()->json($output);
        }else{
            $output = [
                'success' => 0,
                'msg' => __('Invalid token!')
            ];
            return redirect()->back()->with($output);
        }
        
    }
}
