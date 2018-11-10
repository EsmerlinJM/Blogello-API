<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->all();

        // TODO: Validate if email is already in use
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([$validator->errors()], 406);
        }

        // TODO: Register user
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        return response()->json(['status' => 'success', 'user' => $user], 201);
    }

    public function login(Request $request){
        try{
            $data = $request->all();
            // TODO: Find user by username
            $user = User::where('email', $data['email'])->first();
            // TODO: Validate if user exist and password match with password of DB
            if($user && Hash::check($data['password'], $user->password)){
                $user->api_token = Hash::make(str_random(60));
                if($user->save()){
                    return response()->json(['status' => 'logged', 'user' => $user], 200);
                } else {
                    return response()->json(['error' => 'Bad request'], 400);
                }
            } else {
                return response()->json(['error' => 'Invalid credentials'], 406);
            }
        }catch(ModelNotFoundException $e){
            return response()->json(['error' => 'No content'], 406);
        }
    }

    public function logout(Request $request){
        $api_token = explode(' ', $request->header('Authorization'));

        $user = User::where('api_token', $api_token[1])->first();

        if($user){
            $user->api_token = null;

            if($user->save()){
                return response()->json(['success' => 'Logout'], 204);
            }
            return response()->json(['error' => 'Bad request'], 400);
        }
        return response()->json(['error' => 'Not logged in'], 401, []);
    }

    public function auth(Request $request){
        $api_token = explode(' ', $request->header('Authorization'));

        $user = User::where('api_token', $api_token[1])->first();

        if($user){
            return response()->json(['user' => $user], 200);
        }
        return response()->json(['error' => 'Not logged in'], 401, []);
    }
}
