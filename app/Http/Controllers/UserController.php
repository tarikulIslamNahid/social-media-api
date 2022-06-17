<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'=>'bail|required|email',
                'password'=>'bail|required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                if ( Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $token=Auth()->user()->createToken('token')->plainTextToken;
                    return $this->respondWithToken($token);

                }else{
                    return response()->json([
                        'msg' => 'Incorrect login details',
                    ], 401);
                }


            }
        }catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'success' => 'Login Successfully',
            'user' => Auth()->user(),
        ]);
    }
}
