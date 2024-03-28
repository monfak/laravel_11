<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginRegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'phone' => 'required',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8'
        ],[
            'name.required'=> 'نام الزامی است .'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'messages' => $validate->errors(),
                'data' => $validate->errors(),
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;

        $response = [
            'success' => true,
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];

        return response()->json($response);
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'messages' => $validate->errors(),
                'data' => $validate->errors(),
            ], 200);
        }


        $credentials = [
            'phone'=>$request->phone,
            'password'=>$request->password,
        ];
        $token = auth('api')->attempt($credentials);
        if (!$token)
        {
            $response = [
                'success' => false,
                'status' => 'failed',
                'messages' => [
                    'phone'=>'نام کاربری یا رمز عبور اشتباه می باشد .'
                ]
            ];
        }
        else
        {
            $user = auth('api')->user() ;
            $user->permissions = $user->allPermissions()->pluck('name')->toArray() ;
            $data['token'] = $token;
            $data['user'] = $user;

            $response = [
                'success' => true,
                'status' => 'success',
                'message' => 'User is logged in successfully.',
                'data' => $data,
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
        ], 200);
    }

    public function test()
    {
        $user = User::find(1) ;
        $role = Role::find(1);
        dd($user->allPermissions()->pluck('name'),$user->roles,$role->permissions) ;
    }
}