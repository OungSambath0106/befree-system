<?php

namespace App\Http\Controllers\Frontends;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class CustomerForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showFormResetPassword()
    {
        return view('frontends.users.forget-password');
    }

    public function resetPassword(Request $request)
    {
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:customers',

            ]);
            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                $output = [
                    'success' => 0,
                    'msg' => $firstError,
                ];
            
                return redirect()->back()->with($output);
            }
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::to($request->email)->send(new ResetPasswordMail($token));
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('We have e-mailed your password reset link!')
            ];
            return redirect()->back()->with($output);
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
