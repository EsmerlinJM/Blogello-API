<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Board;
use App\Card;
use App\Lists;

class CardController extends Controller
{
    public function index($board_id, $list_id){
        $board = Board::find($board_id);

        if($board){
            if(Auth::user()->id == $board->user_id){
                $list = $board->lists()->find($list_id);
                if($list){
                    return response()->json(['cards' => $list->cards]);
                }
                return response()->json(['error' => 'Not found'], 404, []);
            }
            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
        }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function show($board_id, $list_id, $card_id){
        $board = Board::find($board_id);
            if($board){
                if(Auth::user()->id == $board->user_id){
                    $list = $board->lists()->find($list_id);
                    if($list){
                        $card = $list->cards()->find($card_id);
                        if($card){
                            return response()->json(['status' => 'success', 'card' => $card]);
                        }
                        return response()->json(['error' => 'Not found'], 404, []);
                    }
                    return response()->json(['error' => 'Not found'], 404, []);
                }
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
        return response()->json(['error' => 'Not found'], 404, []);
    }

    public function store(Request $request, $board_id, $list_id){
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
                    $card = $board->lists()->find($list_id)->cards()->create([
                        'name'    => $data['name']
                    ]);
                    return response()->json(['status' => 'success', 'card' => $card], 201);
                }
            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
        return response()->json(['error' => 'Not found'], 404, []);
        
    }

    public function update(Request $request, $card_id){
        $data = $request->all();

        // $validator = Validator::make($data, [
        //     'name' => 'required'
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json([$validator->errors()], 400);
        // }
    
        $card = Card::find($card_id);
            if($card){
                dd($card->list()->board());
                if(Auth::user()->id == $card->list()->board()->user_id){
                    
                    $card = $card->list()->cards()->find($card_id)->updateOrCreate([
                        'id' => $card_id
                    ],
                    [
                        'name' => $data['name'],
                        'description' => $data['description']
                    ]
                    );
                    return response()->json(['message' => 'updated', 'card' => $card], 200);
                }
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
        return response()->json(['error' => 'Not found'], 404, []);    
    }
    public function destroy($card_id){
        $card = Card::find($card_id);
            if($card){
                if(Auth::user()->id == $card->lists()->board()->user_id){
                    if ($card->delete()) {
                        return response()->json(['status' => 'success', 'message' => 'Card deleted successfully']);
                    }
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
                }
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }
            return response()->json(['error' => 'Not found'], 404, []);
    }

}
