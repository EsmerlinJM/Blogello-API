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

    public function boards(){
        return $this->belongsTo(Board::class);
    }
}