<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Reservationstore;
use App\User;

class Reservationstore extends Model
{
    protected $table = 'reservationstores';
    protected $primaryKey = 'ReservationID';
    public $timestamps = true;
    protected $dates = ['ReservationStart','ReservationEnd'];

    public function scopeFilter($filter,$FacultyID,$roomname='',$id='',$year='',$day='',$month=''){
        if($id != ''){
            $filter = $filter->where('StudentID','like','%' .$id. '%');
        }
        if($roomname != ''){
          $filter = $filter->where('RoomName','=',$roomname);
        }
        if($day != ''){
            $filter = $filter->where('ReservationStart','like','%'.$day.' '. '%');
        }
        if($year != ''){
          $filter = $filter->where('ReservationStart','like',$year. '%');
        }
        if($month != ''){
          $filter = $filter->where('ReservationStart','like','%'.'-'.$month.'-'. '%');
        }
        if($FacultyID != ''){
          $filter = $filter->where('Faculty','=',$FacultyID);
        }
        return $filter;
    }       

}
               
