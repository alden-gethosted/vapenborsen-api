<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        try {

            $status = Password::sendResetLink($request->only('email'));

        } catch (\Exception $ex) {
            return response()->json([config('naz.db'), config('naz.db_error')]);
        }

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(["message" => 'Reset password link sent on your email id.']);
        }else{
            dd($status);
        }
    }

    public function get_reset(Request $request){
        $validator = Validator::make($request->all(), [
            'token'          => 'required',
            'email'          => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));

        return response()->json(['data' => $request->all()]);
    }

    public function reset(Request $request){
        $validator = Validator::make($request->all(), [
            'token'          => 'required',
            'email'          => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), config('naz.validation'));
    }
}
