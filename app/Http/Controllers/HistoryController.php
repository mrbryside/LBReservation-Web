<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservationstore;
use App\Room;
use Carbon\Carbon;
use \Auth;
use Excel;
use Illuminate\Support\Facades\Input;
use PHPExcel_Shared_Date;

class HistoryController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
   public function showSearch(Request $request){
        $roomname = '';
        $id = $request->input('id'); 
        $year = Input::get('year'); 
        $yearnow = Carbon::now()->year;
        $day = Input::get('day'); 
        $month = Input::get('month'); 
        $questionId = Input::get('question');
        $FacultyID = Input::get('Faculty');
        if($questionId !=null){ 
            $question = Room::find($questionId);
            $roomname = $question->RoomName;
        }             
        $room =Room::get();
        if($month != '' && $month <10){
            $month = '0'.$month;
        }
        if($day != '' && $day <10){
            $day = '0'.$day;
        }
        if(Input::get('excelButton') != null){
          $Reservationsexcel = Reservationstore::orderBy('created_at','DESC')
                                  ->filter($FacultyID,$roomname,$id,$year,$day,$month)
                                  ->select('StudentID', 'FirstName','LastName','Faculty','RoomName','ReservationStart','ReservationEnd')
                                  ->get();
        
          Excel::create('Reservation History', function($excel) use($Reservationsexcel) {
              $excel->sheet('Sheet 1', function($sheet) use($Reservationsexcel) {
                  $sheet->fromArray($Reservationsexcel);
              });
          })->export('xls');
        }

        $admin = Auth::user()->status;
        $Reservations = Reservationstore::orderBy('created_at','DESC')
                                ->filter($FacultyID,$roomname,$id,$year,$day,$month)
                                ->paginate(10);

        $Reservationsdiff = Reservationstore::orderBy('created_at','DESC')
                                ->filter($FacultyID,$roomname,$id,$year,$day,$month)
                                ->get();


        $min = 0;
        foreach ($Reservationsdiff as $reservation){
          $start = Carbon::parse($reservation->ReservationStart);
          $end = Carbon::parse($reservation->ReservationEnd);
          if($end->toTimeString() == '00:00:00' && $start >= '22:00:00'){
            $end = $end->setTime(23, 59, 59);
          }
          else{
            $start = date("Y-m-d H:i:00",strtotime($start));
            $end = date("Y-m-d H:i:00",strtotime($end));
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
          }
          $min += $start->diffInMinutes($end);
        }
        $diff = floor($min/60);
        $min = $min-(60*$diff);

                                
        $day = $request->input('day');
        $month = $request->input('month');
        return view('history.history')->with('Reservations',$Reservations)
                                      ->with('admin',$admin)
                                      ->with('id',$id)
                                      ->with('year',$year)
                                      ->with('day',$day)
                                      ->with('month',$month)
                                      ->with('room',$room)
                                      ->with('questionId',$questionId)
                                      ->with('FacultyID',$FacultyID)
                                      ->with('dayID',$day)
                                      ->with('monthID',$month)
                                      ->with('yearID',$year)
                                      ->with('yearnow',$yearnow)
                                      ->with('diff',$diff)
                                      ->with('min',$min);
    }
    public function show(Request $request){
        $roomname = '';
        $id = '';
        $year = '';
        $yearnow = Carbon::now()->year;
        $day = '';
        $month = '';
        $questionId = '';
        $FacultyID = '';
        if($questionId !=null){ 
            $question = Room::find($questionId);
            $roomname = $question->RoomName;
        }             
        $room =Room::get();
        if($month != '' && $month <10){
            $month = '0'.$month;
        }
        if($day != '' && $day <10){
            $day = '0'.$day;
        }
        if(Input::get('excelButton') != null){
          $Reservationsexcel = Reservationstore::orderBy('created_at','DESC')
                                  ->select('StudentID', 'FirstName','LastName','Faculty','RoomName','ReservationStart','ReservationEnd')
                                  ->get();
        
          Excel::create('Reservation History', function($excel) use($Reservationsexcel) {
              $excel->sheet('Sheet 1', function($sheet) use($Reservationsexcel) {
                  $sheet->fromArray($Reservationsexcel);
              });
          })->export('xls');
        }

        $admin = Auth::user()->status;
        $Reservations = Reservationstore::orderBy('created_at','DESC')
                                ->paginate(10);

        $Reservationsdiff = Reservationstore::orderBy('created_at','DESC')
                                ->get();


        $min = 0;
        foreach ($Reservationsdiff as $reservation){
          $start = Carbon::parse($reservation->ReservationStart);
          $end = Carbon::parse($reservation->ReservationEnd);
          if($end->toTimeString() == '00:00:00' && $start >= '22:00:00'){
            $end = $end->setTime(23, 59, 59);
          }
          else{
            $start = date("Y-m-d H:i:00",strtotime($start));
            $end = date("Y-m-d H:i:00",strtotime($end));
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
          }
          $min += $start->diffInMinutes($end);
        }
        $diff = floor($min/60);
        $min = $min-(60*$diff);

                                
        $day = '';
        $month = '';
        return view('history.history')->with('Reservations',$Reservations)
                                      ->with('admin',$admin)
                                      ->with('id',$id)
                                      ->with('year',$year)
                                      ->with('day',$day)
                                      ->with('month',$month)
                                      ->with('room',$room)
                                      ->with('questionId',$questionId)
                                      ->with('FacultyID',$FacultyID)
                                      ->with('dayID',$day)
                                      ->with('monthID',$month)
                                      ->with('yearID',$year)
                                      ->with('yearnow',$yearnow)
                                      ->with('diff',$diff)
                                      ->with('min',$min);
    }
}
