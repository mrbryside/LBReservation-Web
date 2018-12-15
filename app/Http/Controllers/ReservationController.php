<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Room;
use App\Usermodel;
use Illuminate\Pagination\Paginator;
use App\Reservation;
use App\Reservationstore;
use App\Notifications\AddReservation;
use App\User;
use App\Ban;
use App\Close;
use Illuminate\Support\Facades\Input;
use Notification;
use Image;
use Mail;
use Carbon\Carbon;
use StreamLab\StreamLabProvider\Facades\StreamLabFacades;

class ReservationController extends Controller
{
    public function store($id,Request $request){
        $true = 0;

        $this->validate($request,[
            'date'=>'required | before:tomorrow + 1 days | after:yesterday',
            'timestart' =>'required',
            'timeend' =>'required',
            'StudentID' => 'required|string|max:10',
                
        ],

        [   
            'date.required' => 'โปรดเลือกวันที่ท่านต้องการจอง',
            'StudentID.required' => 'โปรดกรอกรหัสนิสิต',
            'date.before' => 'ระบบอนุญาติให้จองล่วงหน้าแค่ 1 วัน',
            'date.after' => 'ไม่สามารถจองวันในอดีต',
            'timestart.required' => 'โปรดเลือกเวลาเริ่มใช้งาน ที่ท่านต้องการจอง',
            'timeend.required' => 'โปรดเลือกเวลาเลิกใช้งาน ที่ท่านต้องการจอง',
            
        ]);
        $roomchecknull = Room::find($id);
        if($roomchecknull == null){
            return redirect()->back();
        }
        

        if(Reservation::where('user_id',auth()->user()->id)->first() == null){
            $reservation = new Reservation;
            // $reservationstore = new Reservationstore;
        }
        else if(Auth::user()->status == 0){
            if(Reservation::where('user_id',auth()->user()->id)->first() != null){
                \Session::flash('flash_message','ท่านต้องใช้งานห้องปัจจุบันให้เสร็จ หรือ ลบรายการจองเดิมก่อน จึงจะสามารถจองใหม่ได้');
                return redirect()->back();
            }
        }
        else if(Auth::user()->status == 1 || Auth::user()->status == 2){
            $reservation = new Reservation;
            // $reservationstore = new Reservationstore;
        }
        

    //------------ validate baned user -------------------//
        $bans = Ban::get();
        $userID = Auth::user()->id;

        foreach($bans as $ban){
            
            $banEnd = Carbon::parse($ban->created_at);
            $banEnd->addDays(7);  
            if(Carbon::now() >= $banEnd){
                
                Ban::where('BanID', '=', $ban->BanID)->delete();
                $updateuser = Auth::user();
                $updateuser->CountBan = 0;
                $updateuser->save();
                break;
            }
            if($ban->user_id == $userID && Carbon::now() < $banEnd){
                \Session::flash('flash_message','คุณถูกแบนจากการจอง จนถึงวันที่'.$banEnd->format('d/m/Y').'เวลา '.$banEnd->format('H : i').' เนื่องจากไม่มายืนยันการจอง 3 ครั้ง');
                return redirect()->back();
            }
        }
     //--------------------------------------------------------------------------------------------------------------------------------------------------//
     
     
        
    //------------ validate library close --------------------------------------------------------------------------------------------------------------------//

        $closes = Close::get();

        $date = $request->input('date');
        $date = date('Y-m-d', strtotime($date));

        $Rent = Carbon::parse($date);
        $dateRentStart = $Rent->toDateString();

        $dateRentEnd = Carbon::parse($date);
        $dateRentEnd = $dateRentEnd->toDateString();

        $timestart = $request->input('timestart');
        $timestart = date('H:i', strtotime($timestart));
        $timestart .=':00';

        $timeRentStart = Carbon::parse($timestart);
        $timeRentStart = $timeRentStart->toTimeString();
        if($timeRentStart == '00:00:00'){
            $timeRentStart = '00:00:10';
        }

        $timeend = $request->input('timeend');
        $timeend = date('H:i', strtotime($timeend));
        $timeend .=':00';

        $timeRentEnd = Carbon::parse($timeend);
        $timeRentEnd = $timeRentEnd->toTimeString();
        if($timeRentEnd == '00:00:00'){
            $timeRentEnd = '23:59:59';
        }

        foreach($closes as $close){
            if($close->CloseStart != null && $close->CloseEnd != null){
                $dateCloseStart = Carbon::parse($close->CloseStart);
                $dateCloseStart = $dateCloseStart->toDateString();

                $dateCloseEnd = Carbon::parse($close->CloseEnd);
                $dateCloseEnd = $dateCloseEnd->toDateString();

                $timeCloseStart = Carbon::parse($close->CloseStart);
                $timeCloseStart = $timeCloseStart->toTimeString();
                if($timeCloseStart == '00:00:00'){
                    $timeCloseStart = '00:00:10';
                    $timeCloseStart = Carbon::parse($close->CloseEnd);
                    $timeCloseStart->subDay();
                    $timeCloseStart = $timeCloseStart->toTimeString();
                }

                $timeCloseEnd = Carbon::parse($close->CloseEnd);
                $timeCloseEnd = $timeCloseEnd->toTimeString();
                if($timeCloseEnd == '00:00:00'){
                    $timeCloseEnd = '23:59:59';
                    $timeCloseEnd = Carbon::parse($close->CloseEnd);
                    $timeCloseEnd->subDay();
                    $timeCloseEnd = $timeCloseEnd->toTimeString();
                }

                if($dateRentEnd > $dateCloseStart && $dateRentEnd < $dateCloseEnd){
                    \Session::flash('flash_message','ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'));
                    return redirect()->back();
                }
                else if($dateRentEnd == $dateCloseStart){
                    if($timeRentEnd > $timeCloseStart){
                        if($dateCloseStart == $dateCloseEnd && $timeRentStart < $timeCloseEnd){
                            \Session::flash('flash_message','ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' - '.$close->CloseEnd->format('H : i').' โปรดลองใหม่ในภายหลัง');
                            return redirect()->back();
                        }
                        if($dateCloseStart != $dateCloseEnd){
                            \Session::flash('flash_message','ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'));
                            return redirect()->back();
                        }                   
                    }                            
                }
                else if($dateRentStart == $dateCloseEnd){
                    if($timeRentStart < $timeCloseEnd){
                        \Session::flash('flash_message','ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'));
                        return redirect()->back();
                    }
                }             
            }
        }
    //------------------------------------------------------------------------------------------------------------------------------//

    //------------ room open   --------------------------------------------------------------------------------------------------------------------//

        $rooms = Room::get();
        foreach($rooms as $room){
            if($room->RoomStatus == 1 && $id == $room->Roomid){
                \Session::flash('flash_message','ขณะนี้ '.$room->RoomName.' ปิดให้บริการชั่วคราว โปรดลองใหม่ในภายหลัง');
                return redirect()->back();
            }
        }

    //------------------------------------------------------------------------------------------------------------------------------//


    //------------ validate day open  --------------------------------------------------------------------------------------------------------------------//
        //exam
        $roomfindid = Room::find($id);
        $CloseExam = 0;
        foreach($closes as $close){ 
            if($close->CloseExam == 1){ 
                if($Rent->dayOfWeek != Carbon::SATURDAY && $Rent->dayOfWeek != Carbon::SUNDAY && $roomfindid->RoomPeople != 1 && $roomfindid->RoomFloor == 2 && strpos($roomfindid->RoomName, 'มินิเธียเตอร์') !== false){
                    $timeStart = Carbon::yesterday();
                    $timeStart->addHours(9);
                    $timeStart = $timeStart->toTimeString();

                    $timeEnd = Carbon::yesterday();
                    $timeEnd->addHours(21);
                    $timeEnd->addMinutes(30);
                    $timeEnd = $timeEnd->toTimeString();

                    if($timeRentStart < $timeStart){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30');
                        return redirect()->back();
                    }else if($timeRentStart >=$timeEnd){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 21.30');
                        return redirect()->back();
                    }else if($timeRentEnd > $timeEnd && $timeRentEnd  != '00:00:00'){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30');
                        return redirect()->back();
                    }
                    $CloseExam = 1;
                } 
                else{
                    $timeStart = Carbon::yesterday();
                    $timeStart->addHours(9);
                    $timeStart = $timeStart->toTimeString();

                    $timeEnd = Carbon::yesterday();
                    $timeEnd->addHours(18)->addMinutes(30);
                    $timeEnd = $timeEnd->toTimeString(); 

                    if($timeRentStart < $timeStart){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 18.30');
                        return redirect()->back();
                    }else if($timeRentStart >=$timeEnd){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 18.30');
                        return redirect()->back();
                    }else if($timeRentEnd > $timeEnd){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 18.30');
                        return redirect()->back();
                    }
                    $CloseExam = 1;
                }
            }
        }
        //no exam
        if($CloseExam == 0){
            if($Rent->dayOfWeek === Carbon::SATURDAY) {
                $timeStart = Carbon::yesterday();
                $timeStart->addHours(9);
                $timeStart = $timeStart->toTimeString();

                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(16)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentStart < $timeStart){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }else if($timeRentStart >=$timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }else if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }

            }
            else if($Rent->dayOfWeek === Carbon::SUNDAY){
                $timeStart = Carbon::yesterday();
                $timeStart->addHours(9);
                $timeStart = $timeStart->toTimeString();

                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(16)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentStart < $timeStart){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }else if($timeRentStart >=$timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }else if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }
            }
            else{
                $timeStart = Carbon::yesterday();
                $timeStart->addHours(9);
                $timeStart = $timeStart->toTimeString();

                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(19)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentStart < $timeStart){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30');
                    return redirect()->back();
                }else if($timeRentStart >= $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30');
                    return redirect()->back();
                }else if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30');
                    return redirect()->back();
                }
            }                     
        }                

//------------ validate time > 45 min , <= 120 min , >=now --------------------------------------------------------------------------------------------------------------------//
        $timestart45 = Carbon::parse($timestart);
        $timestart45string = $timestart45->toTimeString();
        $timeend45 = Carbon::parse($timeend);
        $timeend45string = $timeend45->toTimeString();
        if($timestart45->diffInMinutes($timeend45) < 45 ){
            \Session::flash('flash_message','เวลาการจองของคุณน้อยกว่า 45 นาที');
            return redirect()->back();
        } 
        if($timestart45->diffInMinutes($timeend45) > 120 ){
            if($timeend45string != '00:00:00'){
                \Session::flash('flash_message','เวลาการจองของคุณเกิน 2 ชั่วโมง');
                return redirect()->back();    
            }    
        } 

        $timenow = Carbon::now();
        $timenow = $timenow->toTimeString();
        
        $datenow = Carbon::today();
        $datenow = $datenow->toDateString();
        

        if($dateRentStart == $datenow){
            if($timestart45string < $timenow ){
                \Session::flash('flash_message','ไม่สามารถจองเวลาในอดีต');
                return redirect()->back();
            }   
        }
          
        

    //------------------------------------------------------------------------------------------------------------------------------//


 //---------------------  validate overlaptime   ---------------------//

        $alldays = Reservation::where('room_id',$id)->get();
        

        $dat = $request->input('date');
        $dat = date('Y-m-d', strtotime($dat));
        $dayinput = Carbon::parse($dat);
        $dayinput = $dayinput->toDateString();

        $timestart = $request->input('timestart');
        $timestart = date('H:i', strtotime($timestart));
        $timestart .=':00';
        $timestartinput = Carbon::parse($timestart);
        $timestartinput = $timestartinput->toTimeString();

        $timeend = $request->input('timeend');
        $timeend = date('H:i', strtotime($timeend));
        $timeend .=':00';        
        $timeEndinput = Carbon::parse($timeend);
        $timeEndinput = $timeEndinput->toTimeString();
        foreach($alldays as $allday){
            $day = Carbon::parse($allday->ReservationStart);
            $timestart = $day->toTimeString();
            $day = $day->toDateString();

            $day2 = Carbon::parse($allday->ReservationEnd);
            $timeEnd = $day2->toTimeString();

            if($dayinput == $day){
                if($timestartinput < $timestart){
                    if($timeEndinput > $timestart){
                        \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                        return redirect()->back();
                    }
                }
                if($timestartinput == $timestart){
                    \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                    return redirect()->back();
                }
                if($timestartinput > $timestart){
                    if($timestartinput < $timeEnd){
                        \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                        return redirect()->back();
                     }
                }
                if($timeEndinput == '00:00:00' && $timeEnd == '00:00:00'){
                    $timeEndinput = '23:59:00';
                    $timeEnd = '23:59:00';
                    if($timestartinput < $timestart){
                        if($timeEndinput > $timestart){
                            \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                            return redirect()->back();
                            }
                    }
                    if($timestartinput == $timestart){
                        \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                        return redirect()->back();
                    }
                    if($timestartinput > $timestart){
                        if($timestartinput < $timeEnd){
                            \Session::flash('flash_message','เวลานี้มีคนจองแล้ว');
                            return redirect()->back();
                         }
                    }
                }
            }
        }



    //-----------------------end------------------------------// 
        
       
        $date = $request->input('date');
        $timestart = $request->input('timestart');
        $timestart = date('H:i', strtotime($timestart));
        $timestart .=':00';
        $date = date('Y-m-d'.$timestart, strtotime($date));

        $UserID = Auth::user()->id;
        $StudentID = Auth::user()->StudentID;
        $room = $id;

        $roomName = Room::find($id);
        $roomName = $roomName->RoomName;

        $carbon_date = $request->input('date');
        $timeend = $request->input('timeend');
        $timeend = date('H:i', strtotime($timeend));
        $timeend .=':00';
        if($timeend == "00:00:00"){
            $carbon_date = Carbon::parse($timeend);
            $carbon_date->addDay();
        }
        else{
            $carbon_date = date('Y-m-d'.$timeend, strtotime($carbon_date));
        }
        $user = User::where('StudentID',$request->input('StudentID'))->first();
        if($user != null){            
            $userall = Reservation::where('user_id',$user->id)->get();
        }
        
        if($user == null){
            if($request->input('StudentID') != 'myself'){
                \Session::flash('flash_message','รหัสนิสิตไม่ตรงกับข้อมูลในระบบ!');
                return redirect()->back();
            }
        }
        if($user != null && auth()->user()->status != 0){
            if(count($userall)>=1){
                \Session::flash('flash_message','ไม่สามารถจองห้องให้นิสิตที่มีรายการจองอยู่แล้ว!');
                return redirect()->back();
            }
            $reservation->ReservationStart = $date;
            $reservation->ReservationEnd = $carbon_date;
            $reservation->user_id = $user->id;
            $reservation->student_id = $user->StudentID;
            $reservation->room_id = $room;
        }
        else{
            $reservation->ReservationStart = $date;
            $reservation->ReservationEnd = $carbon_date;
            $reservation->user_id = $UserID;
            $reservation->student_id = $StudentID;
            $reservation->room_id = $room;
        }

        if($reservation->save()){
            $admin= User::get()->where('status',1);
            $staff = User::get()->where('status',2);
            Notification::send($admin , new AddReservation($reservation));
            Notification::send($staff , new AddReservation($reservation));
            $today = $reservation->ReservationStart;
            if(auth()->user()->id != $reservation->user_id){
                $data = '<font size="2.5"><b>'.auth()->user()->Firstname.' '.auth()->user()->Lastname.' ('.auth()->user()->StudentID.')</b></font> ได้จอง(ให้นิสิต) <font size="2.5"><b>'.$reservation->room->RoomName.'</b></font> <br>'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$today->format('d/m/Y').' '.'<font size="3">เวลา '.$reservation->ReservationStart->format('H : i').' - '.$reservation->ReservationEnd->format('H : i')."</font>";
            }
            else{
                $data = '<font size="2.5"><b>'.auth()->user()->Firstname.' '.auth()->user()->Lastname.' ('.auth()->user()->StudentID.')</b></font> ได้จอง <font size="2.5"><b>'.$reservation->room->RoomName.'</b></font> <br>'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$today->format('d/m/Y').' '.'<font size="3">เวลา '.$reservation->ReservationStart->format('H : i').' - '.$reservation->ReservationEnd->format('H : i')."</font>";
            }
            StreamLabFacades::pushMessage('test','AddReservation',$data);
        }
        

        \Session::flash('flash_message2','การจองสำเร็จ! สามารถดู/ยกเลิก รายการจอง ในเมนู My Reservation');
        return redirect()->back(); 
        
    }
    public function AllSeen(){
        foreach(auth()->user()->unreadnotifications as $note){
            $note->markAsRead();
        }
    }
    public function AddTime($id){
        $admin = Auth::user()->status;
        if($id == null){
            return redirect()->back();
        }
        return view('reservationSearch.addtime')->with('ReserID',$id)
                                                ->with('admin',$admin);
    }
    public function AddTimeUpdate(Request $request, $id){
        $this->validate($request,[
            'AddTime'=>'required|integer|between:30,60',
                
        ],

        [   
            'AddTime.between' => 'เวลาต้องอยู่ในช่วง 30 - 60 นาทีเท่านั้น',
            
        ]);

        $addtime = $request->input('AddTime');
        $Reservation = Reservation::find($id);
        if($Reservation == null){
            return redirect()->back();
        }
        $ReservationCarbon = Carbon::parse($Reservation->ReservationEnd);
        $ReservationEnd = $ReservationCarbon->addMinutes($addtime);
        $ReservationEndTime = $ReservationEnd->toTimeString();
        $ReservationEndDate = $ReservationCarbon->toDateString();
        
        $ReservationStart = Carbon::parse($Reservation->ReservationStart);
        $ReservationStart = $ReservationStart->toTimeString();

        $alldays = Reservation::where('room_id' ,'=',$Reservation->room->Roomid)
                               ->where('ReservationID','!=',$id)
                               ->Where('ReservationStart','>',$Reservation->ReservationStart)
                               ->get();

        foreach($alldays as $allday){
            $day = Carbon::parse($allday->ReservationStart);
            $timestart = $day->toTimeString();
            $day = $day->toDateString();

            $day2 = Carbon::parse($allday->ReservationEnd);
            $timeEnd = $day2->toTimeString();

            if($ReservationEndDate == $day){
                if($ReservationEndTime > $timestart  && $allday->ReservationID != $id){
                    \Session::flash('flash_message','การต่อเวลาซ้อนกับรายการจองถัดไป');
                    return redirect()->back();
                }
            }
        }

        $timeRentStart = $Reservation->ReservationStart;
        $timeRentStart = Carbon::parse($timeRentStart);
        $timeRentStart = $timeRentStart->toTimeString();
        $timeRentEnd = $ReservationEndTime;

        $Rent = Carbon::parse($Reservation->ReservationEnd);

        $closes = Close::get();
        $roomfindid = Room::find($Reservation->room_id);
        $CloseExam = 0;
        foreach($closes as $close){ 
            if($close->CloseExam == 1){ 
                if($Rent->dayOfWeek != Carbon::SATURDAY && $Rent->dayOfWeek != Carbon::SUNDAY && $roomfindid->RoomPeople != 1 && $roomfindid->RoomFloor == 2 && strpos($roomfindid->RoomName, 'มินิเธียเตอร์') !== false){
                    $timeEnd = Carbon::yesterday();
                    $timeEnd->addHours(21)->addMinutes(30);
                    $timeEnd = $timeEnd->toTimeString();
                    if($timeRentEnd > $timeEnd){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30');
                        return redirect()->back();
                    }
                    $CloseExam = 1;
                        
                }
                else{
                    $timeEnd = Carbon::yesterday();
                    $timeEnd->addHours(18)->addMinutes(30);
                    $timeEnd = $timeEnd->toTimeString();

                    if($timeRentEnd > $timeEnd){
                        \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 18.30');
                        return redirect()->back();
                    }
                    $CloseExam = 1;
                }   
            }
        }
        //no exam
        if($CloseExam == 0){
            if($Rent->dayOfWeek === Carbon::SATURDAY) {
                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(16)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }

            }
            else if($Rent->dayOfWeek === Carbon::SUNDAY){
                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(16)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30');
                    return redirect()->back();
                }
            }
            else{

                $timeEnd = Carbon::yesterday();
                $timeEnd->addHours(19)->addMinutes(30);
                $timeEnd = $timeEnd->toTimeString();

                if($timeRentEnd > $timeEnd){
                    \Session::flash('flash_message','เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30');
                    return redirect()->back();
                }
            }                        
        }   

        $Reservation->ReservationEnd = $ReservationEnd;
        $Reservation->save();


        \Session::flash('flash_message2','ต่อเวลาสำเร็จ!');
        return redirect()->to('/reservationsearch');
    }
    public function destroy($id,$where){
        $reservation = Reservation::find($id);

        if($reservation != null){

            $day = Carbon::parse($reservation->ReservationStart);

            $time = Carbon::parse($reservation->ReservationStart);

            $timenow = Carbon::now();
            $timenow = $timenow->toTimeString();

            $daynow = Carbon::now();
            $daynow = $daynow->toDateString();
            if ($where == 'myreserve') {
                if($reservation->user_id != Auth::user()->id){
                    \Session::flash('flash_message','ไม่สามารถลบการจองของผู้อื่น');
                    return redirect()->back();
                }
                if ($time->subMinutes(15) <= Carbon::now()) {
                    \Session::flash('flash_message','ไม่สามารถยกเลิกการจอง ในช่วง 15 นาทีก่อนเวลาใช้งานได้');
                    return redirect()->back();
                }
                if($reservation->ActiveStatus == 1){
                    \Session::flash('flash_message','ไม่สามารถยกเลิกการจองห้อง ที่กำลังใช้งานอยู่');
                    return redirect()->back();
                }
            }
            if($where == 'search'){
                if(Auth::user()->status == 0){
                    \Session::flash('flash_message','คุณไม่ใช่ผู้ดูแลไม่สามารถลบการจองได้!');
                    return redirect()->back();
                }
                if($reservation->ActiveStatus == 1){
                    $reservations = Reservation::get();
                    foreach($reservations as $reservation){
                        if($reservation->ReservationID == $id){
                            $reservationstore = new Reservationstore;
                            $reservationstore->ReservationStart = $reservation->ReservationStart;
                            $reservationstore->ReservationEnd = Carbon::now();
                            $reservationstore->StudentID = $reservation->user->StudentID;
                            $reservationstore->RoomName = $reservation->room->RoomName;
                            $reservationstore->FirstName = $reservation->user->Firstname;
                            $reservationstore->LastName = $reservation->user->Lastname;
                            $reservationstore->Faculty = $reservation->user->Faculty;
                            $reservationstore->save();
                        }
                    }
                }
            }
          
            $destroy = Reservation::where('ReservationID',$id)->delete();

            \Session::flash('flash_message2','ลบรายการจองสำเร็จ!');
            return redirect()->back();
            //------------------------------------------------------------------------------------------------------------------------------------------------------//
        }
        else{
            \Session::flash('flash_message','รายการจองนี้ถูกลบไปแล้ว!');
            return redirect()->back();
        }
        

        
    }
    public function update($id){

        $update = Reservation::find($id);
        if($update == null){
            return redirect()->back();
        }
        if($update->ActiveStatus == 0){
            $reserveStart = Carbon::parse($update->ReservationStart);
            if($reserveStart->subMinutes(15) <= Carbon::now()){

                
                $update->ActiveStatus = 1;


                // $reservationstores = Reservationstore::get();
                $reservation = Reservation::find($id);
                // $storeID = 0;

            //------------------------------update time in reservationstore --------------------------------------------------------------------------------------//
                // foreach($reservationstores as $reservationstore){
                //     if($reservation->ReservationStart == $reservationstore->ReservationStart && $reservationstore->RoomName == $reservation->room->RoomName){
                //         if($reservation->user->StudentID == $reservationstore->StudentID){
                //             $storeID = $reservationstore->ReservationID;
                //         }
                //     }
                // }
                // $updatestore = Reservationstore::find($storeID);
                // $updatestore->ReservationStart = Carbon::now();   
                $update->ReservationStart = Carbon::now();
                // $updatestore->save();
                $update->save();

            //------------------------------------------------------------------------------------------------------------------------------------------------------//
            }
            else{
                \Session::flash('flash_message','ดำเนินการไม่สำเร็จ! สามารถ Active ได้เมื่อ 15 นาทีก่อนถึงเวลาใช้งาน');
                return redirect()->back();
            }
            \Session::flash('flash_message2','ยืนยันสำเร็จ!');
            return redirect()->back();
        }
        else{
            \Session::flash('flash_message','รายการจองนี้ได้รับการยืนยันไปแล้ว!');
            return redirect()->back();
        }
    }
    public function myreservation(){
        $today = Carbon::now();
        $admin = Auth::user()->status;
        $id = Auth::user()->id;
        $myReservations = Reservation::orderBy('ReservationStart')
                                ->where('user_id',$id)
                                ->where('ReservationEnd','>=',$today)
                                ->get();

        // $myReservations = $myReservations->where('user_id',3);



        
        return view('home.myreservation')->with('Reservations',$myReservations)
                                         ->with('admin',$admin);
    }
}

