<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index(Request $request){
        // Eloquent method
        $user = User::all();
        return response()->json([$user], 200);   
    }

    public function store(Request $request){
            // TODO: Create user on save in the DB
            // $data = $request->json()->all();
            $data = $request->all();

            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'api_token' => str_random(60),
            ]);
            return response()->json([$user], 201);   
    }

    public function update(Request $request, $id){
            $data = $request->all();
            $user = User::find($id);
            if(!$user){
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

    public function destroy(Request $request, $id){
            $user = User::findOrFail($id);
                // TODO: Create delete user in the DB
                if($user->delete()){
                    return response()->json(['success' => 'Deleted'], 202); 
                } else {
                    return response()->json(['Error' => 'Bad Request'], 400); 
                }
            return response()->json(['error' => 'Not Found'], 404, []);      
    }

    public function getToken(Request $request){
            try{
                $data = $request->all();
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
}
