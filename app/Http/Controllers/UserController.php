<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
   public function get_me(Request $request)
   {
       $user =  auth()->user() ;
       if ($user)
       {
           $user->permissions = $user->allPermissions()->pluck('name')->toArray() ;
       }

       return $user ? [
           'success'=>true,
           'data'=>[
               'user'=>$user
           ]
       ] : [
           'success'=>false,
           'data'=>[]
       ];
   }
}