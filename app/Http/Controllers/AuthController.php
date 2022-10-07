<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;


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

    
    
   
    public function destroy($id)
    {
        
    }
}
