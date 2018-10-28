<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model {
    protected $table = 'boards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function lists(){
        return $this->hasMany(Lists::class);
    }
}