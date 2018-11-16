<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model {
    protected $table = 'lists';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'board_id'
    ];

    public function board(){
        return $this->hasMany(Board::class);
    }

    public function cards(){
    	return $this->hasMany(Card::class);
    }
}