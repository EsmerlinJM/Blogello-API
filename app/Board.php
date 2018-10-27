<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }
}