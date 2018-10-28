<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Board;

class ListController extends Controller
{
    public function index($board_id){
        
        $board = Board::find($board_id);

        if($board){
            if(Auth::user()->id == $board->user_id){
                $list = $board->lists;
                return response()->json(['lists' => $list], 200);
            }
            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
        }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function show($board_id, $list_id){
        $board = Board::find($board_id);

        if($board){
            if(Auth::user()->id == $board->user_id){
                $list = $board->lists()->find($list_id);
                if($list){
                    return response()->json(['status' => 'success', 'list' => $list]);
                }
                return response()->json(['error' => 'Not Found'], 404, []);
            }
            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
        }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function store(Request $request, $board_id){
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([$validator->errors()], 400);
        }
        
        $board = Board::find($board_id);

            if($board){
                if(Auth::user()->id == $board->user_id){
                    $board->lists()->create([
                        'name'    => $data['name'],
                    ]);
                    return response()->json(['status' => 'success', 'board' => $board], 201);
                }
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function update(Request $request, $board_id, $list_id){
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required'
        ]);

        $board = Board::find($board_id);

        if($board){
            if(Auth::user()->id == $board->user_id){
                $board = Auth::user()->boards()->updateOrCreate([
                    'id' => $board_id
                ],
                [
                    'name' => $data['name'],
                ]
            );
                return response()->json(['status' => 'updated'], 202);
            }
            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
        }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function destroy($board_id, $list_id){
        $board = Board::find($board_id);

            if($board){
                if(Auth::user()->id == $board->user_id){
                    $list = $board->lists()->find($list_id);
                    if($list){
                        if ($list->delete()) {
                            return response()->json(['status' => 'success', 'message' => 'List deleted successfully']);
                        }
                        return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 400); 
                    }
                    return response()->json(['error' => 'Not Found'], 404, []);
                }
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
        return response()->json(['error' => 'Not found'], 404, []);
    }
}
