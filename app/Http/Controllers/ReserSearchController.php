<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\Room;
use App\Usermodel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use \Auth;

class ReserSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   public function show(Request $request){
        $id = $request->input('id');
        $roomid = '';
        $onadmin = 0;
        $today = Carbon::now();
        $admin = Auth::user()->status;
        $room = Room::get();
        $questionId = Input::get('question');
        if($questionId !=null){
            $question = Room::find($questionId);
            $roomid = $question->Roomid;
        } 
        $Reservations = Reservation::orderBy('ReservationStart')
                                ->where('ReservationEnd','>=',$today)
                                ->filter($id,$roomid)
                                ->paginate(10);

        return view('reservationSearch.search')->with('Reservations',$Reservations)
                                      ->with('admin',$admin)
                                      ->with('id',$id)
                                      ->with('onadmin',$onadmin)
                                      ->with('room',$room)
                                      ->with('questionId',$questionId);
    }
    public function showonadmin(Request $request){
        $id = $request->input('id');
        $roomid = '';
        $onadmin = 1;
        $today = Carbon::now();
        $admin = Auth::user()->status;
        $room = Room::get();
        $questionId = Input::get('question');
        if($questionId !=null){
            $question = Room::find($questionId);
            $roomid = $question->Roomid;
        } 
        $Reservations = Reservation::orderBy('ReservationStart')
                                ->where('ReservationEnd','>=',$today)
                                ->filter($id,$roomid)
                                ->paginate(10);

        return view('reservationSearch.search')->with('Reservations',$Reservations)
                                      ->with('admin',$admin)
                                      ->with('id',$id)
                                      ->with('onadmin',$onadmin)
                                      ->with('room',$room)
                                      ->with('questionId',$questionId)
                                      ->with('onadmin',$onadmin);
    }
}
