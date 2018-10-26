<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Board;

class BoardController extends Controller
{
    
    public function index(){
        $board = Board::all();
        return response()->json([$board], 200);
    }

    public function store(Request $request){
        $data = $request->all();

        $board = Board::create([
            'name' => $data['name'],
            // 'user_id' => $data['user_id']
            'user_id' => 1
        ]);

        return response()->json([$board], 201);
    }
}
