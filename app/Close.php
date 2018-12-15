<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Close extends Model
{
    protected $table = 'closes';
    protected $primaryKey = 'CloseID';
    public $timestamps = true;
    protected $dates = ['CloseStart','CloseEnd'];


    public function room(){
    	return $this->belongsTo('App\Room','room_id');
    }
}
