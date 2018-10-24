<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index(Request $request){

        if ($request->isJson()) {
            // Eloquent method
            $user = User::all();
            return response()->json([$user], 200);   
        } 
        
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }

    public function store(Request $request){
        if ($request->isJson()) {
            // TODO: Create user on save in the DB
            $data = $request->json()->all();

            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'api_token' => str_random(60),
            ]);

            return response()->json([$user], 201);   
        } 
        
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }

    public function update(Request $request, $id){
        if ($request->isJson()) {
            $data = $request->json()->all();
            $user = User::find($id);
            if($user != null){
                // TODO: Create update or create user in the DB
                $user = User::updateOrCreate([
                    'id' => $id
                ],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'api_token' => str_random(60),
                ]
            );
                return response()->json(['success' => 'Updated'], 200);  
            } else {
                return response()->json(['error' => 'Not Found'], 404, []);  
            }
             
        }
        return response()->json(['error' => 'Unauthorized'], 401, []);    
    }

    public function destroy(Request $request, $id){
        if ($request->isJson()) {
            $user = User::find($id);
            if($user != null){
                // TODO: Create delete user in the DB
               if($user->delete()){
                 return response()->json(['success' => 'Deleted'], 202); 
               } else {
                 return response()->json(['Error' => 'Bad Request'], 400); 
               }
                 
            } else {
                return response()->json(['error' => 'Not Found'], 404, []);  
            }
             
        }
        return response()->json(['error' => 'Unauthorized'], 401, []); 
    }

    public function getToken(Request $request){
        if($request->isJson()){
            try{
                $data = $request->json()->all();
                // TODO: Find user by username
                $user = User::where('username', $data['username'])->first();
                // TODO: Validate if user exist and password match with password of DB
                if($user && Hash::check($data['password'], $user->password)){
                    return response()->json([$user], 200);
                } else {
                    return response()->json(['error' => 'No content'], 406);
                }
            }catch(ModelNotFoundException $e){
                return response()->json(['error' => 'No content'], 406);
            }
        } 
        return response()->json(['error' => 'Unauthorized'], 401, []);
    }
}
