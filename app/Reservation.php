<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'ReservationID';
    public $timestamps = true;
    protected $dates = ['ReservationStart','ReservationEnd'];


    public function room(){
    	return $this->belongsTo('App\Room','room_id');
    }
    public function user(){
    	return $this->belongsTo('App\User');
    }
    public function scopeFilter($filter,$s,$roomid){
        if($s != ''){
          $filter = $filter->where('student_id','like','%' .$s. '%');
        }
        if($roomid != ''){
          $filter = $filter->where('room_id','=',$roomid);
        }
        return $filter;
    }

}
