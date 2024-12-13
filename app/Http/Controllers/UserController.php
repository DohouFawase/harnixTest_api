<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller 
{
    //Creation des donner utlisateur dans la base de donné

    //Register (Inscription)

    public function Register (Request $request) {
        $validator = Validator::make($request->all(), [
         'name'   => "required|string",
         "email" => "required|email|unique:users,email",
         'password'   => "required|string|min:8"
        ]);

        $createUser  =  User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password'))
        ]);

        return response()->json([
            "createUser" =>$createUser,
            "message"=> "Registered succefully",
        ], 201);
        
    }


    public function Login(Request $request) {
        $creditienal = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

          // Ajouter des claims personnalisés
  

       if(!$token = JWTAuth::attempt($creditienal)) {
        return response()->json(['error' => 'invalid_credentials'], 401);
       } 


       $user = Auth::user();

       $customClaims = [
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
    ];

    $token = JWTAuth::claims($customClaims)->attempt($creditienal);

      return response()->json([
        'token' => $token,
        'user' => [
            'name' => $user->name,
            'email' => $user->email, // You can include other user details as needed
            'role' => $user->role, // You can include other user details as needed
        ],
        "message" => "Login succesfuly"
      ]);    
    }


    public function Logout(Request $request) {
       // Invalidate the token
       JWTAuth::invalidate(JWTAuth::getToken());
            
       // Return a success message
       return response()->json([
           'message' => 'Logout successful'
       ], 200);
    }
    


  

}
