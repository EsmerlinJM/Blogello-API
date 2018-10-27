<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    public function index(){
        // Eloquent method
        $user = User::all();
        return response()->json([$user], 200);   
    }

    public function show($id){
        // TODO: Get user by id
        $user = User::find($id);
        if($user){
            return response()->json([$user], 200);
        }
        return response()->json(['error' => 'Not Found'], 404, []);
    }

    public function store(Request $request){
            // TODO: Create user on save in the DB
            $data = $request->all();

            $validator = Validator::make($data, [
                'email' => 'required|email|unique:users,email'
            ]);
        
            if ($validator->fails()) {
                return response()->json([$validator->errors()], 400);
            }

            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'api_token' => str_random(60),
            ]); 
            return response()->json(['status' => 'success', 'user' => $user], 201);
    }

    public function update(Request $request, $id){
            
            $data = $request->all();

            $validator = Validator::make($data, [
                'email' => 'required|email|unique:users,email' . ($id ? ",$id" : '')
            ]);
        
            if ($validator->fails()) {
                return response()->json([$validator->errors()], 400);
            }
            
            // TODO: Create update or create user in the DB
            $user = User::updateOrCreate([
                'id' => $data['id']
            ],
            [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'api_token' => str_random(60),
            ]
        );
            return response()->json(['status' => 'Updated'], 202);  
    }

    public function destroy(Request $request, $id){
            $user = User::findOrFail($id);
                // TODO: Create delete user in the DB
                if($user->delete()){
                    return response()->json(['status' => 'Deleted'], 202); 
                } else {
                    return response()->json(['error' => 'Bad Request'], 400); 
                }
            return response()->json(['error' => 'Not Found'], 404, []);      
    }
}
