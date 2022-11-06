<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;



class AuthController extends Controller
{
    

   
    public function register(Request $request)
    {
       
        $validInput = $request->validate([
            'email' => 'required|email|max:50|unique:users',
            'role'=>'required',
            'name'=>'required|max:50',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create($validInput);
        event(new Registered($user));
       
        return ['token' =>  $user->createToken('auth_token')->plainTextToken, "user"=>$user];


    }

   
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
      
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['The provided credentials are incorrect.'],
            ]);
        }
     
        return ['token'=>$user->createToken('auth_token')->plainTextToken, 'user'=>$user];
       
    }

    public function forgot(Request $request) {

        $input = $request->validate(['email' => 'required|email']);
        $registered_user = DB::table('users')->where('email', $request->email)->first();
        if(!$registered_user){
            throw ValidationException::withMessages([
                'msg' => ['We couldn\'t find an account with that email adress'],
            ]);
        }

        $status = Password::sendResetLink($input);
            return $status === "passwords.sent"
                    ? response()->json(["msg" => 'Please check your email address for a reset password'])
                    :response()->json(["msg" => 'Error request reset password, Please try again later'],500);
      
         
    }

    

    public function changeForgottenPassword(Request $request){
       

       $emailWithResetToken =  DB::table('password_resets')->where('email', $request->email)->first();
      
        if(!$emailWithResetToken){
            return response() -> json(["msg"=>"Request not found. please try again"],404);
        }


        if(!Hash::check($request->token, $emailWithResetToken->token)){
            return response() -> json(["Invalid token, please try again"],404);
        }

        $user= User::where('email', $request->email)->first();
      
        if($user){

            //save password
            $user->password = bcrypt($request->password);
            $user->save();

            //delete reset password token
            DB::delete('DELETE FROM password_resets WHERE token = ?', [$emailWithResetToken->token]);
            
            return response()->json(["user"=>$user]);
        }
    
    }

    
    
   
    public function destroy($id)
    {
        
    }
}
