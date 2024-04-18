<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Validator;

class AccountController extends Controller
{
    public static function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' );

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }
   public function update(Request $request,$id)
   {
       $validate = Validator::make($request->all(), [
           'name' => 'required|string|max:250',
           'phone' => 'required',
           'email' => 'required|string|email:rfc,dns|max:250',
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
       $user = User::find($id) ;
       if ($user->id == auth()->id() || auth()->user()->hasPermission('admin'))
       {
           $user->name = $request->name ;
           $user->phone = $request->phone ;
           $user->email = $request->email ;
           $user->password = Hash::make($request->password) ;
           $user->save() ;
           if ($request->avatar_file)
           {
               $file =  $request->avatar_file;
               $path = public_path() . '/files/tickets';
               if (!File::isDirectory($path))
               {
                   File::makeDirectory($path, 0777, true, true);
               }
               $imgName = 'img_' . time() . '.' . $file->getClientOriginalExtension();
               $file->move($path, $imgName);

               $user->avatar = '/files/tickets/'.$imgName;
               $user->save();

           }
           $user->permissions = $user->allPermissions()->pluck('name')->toArray() ;
           $data['token'] = auth()->refresh();
           $data['user'] = $user;
           return[
               'success'=>true,
               'status' => 'success',
               'message' => 'User is created successfully.',
               'data' => $data,
           ];
       }
   }
}