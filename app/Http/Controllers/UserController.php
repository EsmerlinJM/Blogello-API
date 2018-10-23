<?php

namespace App\Http\Controllers;
use App\User;

class UserController extends Controller
{
    public function index(){
        $user = User::all();

        return response()->json([$user], 200);   
    }
}
