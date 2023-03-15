<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request  $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:10'
        ]);

        if ($validator->fails()){
            $error = $validator->errors();
            return response()->json([
                'error'=>$error,
            ], 400);
        }

        if($validator->passes()){
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token'=>$token,
                'token_type'=>'Bearer'
            ]);
        }
    }



}
