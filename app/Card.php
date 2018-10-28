<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model {
    protected $table = 'cards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'list_id', 'description'
    ];

    public function list(){
    	return $this->belongsTo(Lists::class);
    }
}