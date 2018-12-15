<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use Illuminate\Support\Facades\Validator;
use App\Reservation;
use \Auth;
use App\Usermodel;
use Illuminate\Pagination\Paginator;
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
use App\Plan;

class ApiRoomController extends Controller
{
    public function roomList(Request $request,$roomType){
        $res = [];
        if($roomType == "shared"){
            $rooms = Room::where('RoomPeople','>',1)->get();
        }
        else if($roomType  == "single"){
            $rooms = Room::where('RoomPeople',1)->get();
            $plan= Plan::first();
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'error roomtype'
            ]);
        }
        foreach($rooms as $room){
            array_push($res,[
                'room_id'=>$room->Roomid,
                'room_name'=>$room->RoomName,
                'room_des'=>$room->RoomDescription,
                'room_people'=>$room->RoomPeople,
                'room_floor'=>$room->RoomFloor,
                'room_status'=>$room->RoomStatus,
                'image_url'=>env('APP_URL','').'/thumbnails'.'/'.$room->ImageName,
            ]);
        }
        
        if($roomType  == "single"){
            return response()->json([
                'success' => true,
                'message' => $roomType,
                'planImg' => env('APP_URL','').'/seimg'.'/'.$plan->ImageName,
                'data' => $res
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => $roomType,
                'data' => $res
            ]);
        }
        
        
    }
    public function roomSearch(Request $request){
        $validator = Validator::make($request->all(),[
            'word'=>'required',
    	]);

    	if($validator->fails()){
    		return response()->json(['error' => $validator->errors()], 401);
        }
        $rooms = Room::where('RoomName', 'like', $request->input('word').'%')
                      ->orwhere('RoomName', 'like', '%'.$request->input('word').'%')
                      ->orWhere('RoomPeople','=',$request->input('word'))->get();

        $res=[];
        foreach($rooms as $room){
            array_push($res,[
                'room_id'=>$room->Roomid,
                'room_name'=>$room->RoomName,
                'room_des'=>$room->RoomDescription,
                'room_people'=>$room->RoomPeople,
                'room_floor'=>$room->RoomFloor,
                'room_status'=>$room->RoomStatus,
                'image_url'=>env('APP_URL','').'/thumbnails'.'/'.$room->ImageName,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'data' => $res
        ]);
        
    }
    public function reserved(Request $request,$roomID,$student_ID,$userID){
        $true = 0;
        $validator = Validator::make($request->all(),[
            'date'=>'required | before:tomorrow + 1 days | after:yesterday',
            'timestart' =>'required',
            'timeend' =>'required',
    	]);

    	if($validator->fails()){
    		return response()->json(['error' => $validator->errors()], 401);
        }
        
        $roomchecknull = Room::find($roomID);
        if($roomchecknull == null){
            return redirect()->back();
        }
        

        if(Reservation::where('user_id',auth()->user()->id)->first() == null){
            $reservation = new Reservation;
            // $reservationstore = new Reservationstore;
        }
        else if(Auth::user()->status == 0){
            if(Reservation::where('user_id',auth()->user()->id)->first() != null){
                return response()->json([
                    'success' => false,
                    'message' => 'ท่านต้องใช้งานห้องปัจจุบันให้เสร็จ หรือ ลบรายการจองเดิมก่อน จึงจะสามารถจองใหม่ได้',
        
                ]);
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
                return response()->json([
                    'success' => false,
                    'message' => 'คุณถูกแบนจากการจอง จนถึงวันที่'.$banEnd->format('d/m/Y').'เวลา '.$banEnd->format('H : i').' เนื่องจากไม่มายืนยันการจอง 3 ครั้ง',
        
                ]);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'),
            
                    ]);
                }
                else if($dateRentEnd == $dateCloseStart){
                    if($timeRentEnd > $timeCloseStart){
                        if($dateCloseStart == $dateCloseEnd && $timeRentStart < $timeCloseEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' - '.$close->CloseEnd->format('H : i').' โปรดลองใหม่ในภายหลัง',
                    
                            ]);
                        }
                        if($dateCloseStart != $dateCloseEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'),
                    
                            ]);
                        }                   
                    }                            
                }
                else if($dateRentStart == $dateCloseEnd){
                    if($timeRentStart < $timeCloseEnd){
                        return response()->json([
                            'success' => false,
                            'message' => 'ระบบปิดบริการจองชั่วคราวใน วันที่ '.$close->CloseStart->format('d/m/Y').' เวลา '.$close->CloseStart->format('H : i').' จนถึง วันที่ '.$close->CloseEnd->format('d/m/Y').' เวลา '.$close->CloseEnd->format('H : i'),
                
                        ]);
                    }
                }             
            }
        }
    //------------------------------------------------------------------------------------------------------------------------------//

    //------------ room open   --------------------------------------------------------------------------------------------------------------------//

        $rooms = Room::get();
        foreach($rooms as $room){
            if($room->RoomStatus == 1 && $roomID == $room->Roomid){
                return response()->json([
                    'success' => false,
                    'message' => 'ขณะนี้ '.$room->RoomName.' ปิดให้บริการชั่วคราว โปรดลองใหม่ในภายหลัง',
        
                ]);
            }
        }

    //------------------------------------------------------------------------------------------------------------------------------//


    //------------ validate day open  --------------------------------------------------------------------------------------------------------------------//
        //exam
        $roomfindid = Room::find($roomID);
        $CloseExam = 0;
        foreach($closes as $close){ 
            if(($close->CloseExam == 1 && $roomfindid->RoomFloor == 2) || ($close->CloseExam == 1 && $roomfindid->RoomPeople == 1)){ 
                if(strpos($roomfindid->RoomName, 'มินิเธียเตอร์') !== false){
                    //do nothing
                }
                else{
                    if($Rent->dayOfWeek === Carbon::SATURDAY) {
                        $timeStart = Carbon::yesterday();
                        $timeStart->addHours(9);
                        $timeStart = $timeStart->toTimeString();

                        $timeEnd = Carbon::yesterday();
                        $timeEnd->addHours(18)->addMinutes(30);
                        $timeEnd = $timeEnd->toTimeString(); 

                        if($timeRentStart < $timeStart){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }else if($timeRentStart >=$timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }else if($timeRentEnd > $timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }
                    }
                    else if($Rent->dayOfWeek === Carbon::SUNDAY){
                        $timeStart = Carbon::yesterday();
                        $timeStart->addHours(9);
                        $timeStart = $timeStart->toTimeString();

                        $timeEnd = Carbon::yesterday();
                        $timeEnd->addHours(18)->addMinutes(30);
                        $timeEnd = $timeEnd->toTimeString();

                        if($timeRentStart < $timeStart){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }else if($timeRentStart >=$timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }else if($timeRentEnd > $timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 18.30',
                    
                            ]);
                        }
                    }
                    else{
                        $timeStart = Carbon::yesterday();
                        $timeStart->addHours(9);
                        $timeStart = $timeStart->toTimeString();

                        $timeEnd = Carbon::yesterday();
                        $timeEnd->addHours(21);
                        $timeEnd->addMinutes(30);
                        $timeEnd = $timeEnd->toTimeString();

                        if($timeRentStart < $timeStart){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30',
                    
                            ]);
                        }else if($timeRentStart >=$timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30',
                    
                            ]);
                        }else if($timeRentEnd > $timeEnd && $timeRentEnd  != '00:00:00'){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 21.30',
                    
                            ]);
                        }
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
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);
                }else if($timeRentStart >=$timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);;
                }else if($timeRentEnd > $timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันเสาร์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);
                }else if($timeRentStart >=$timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);
                }else if($timeRentEnd > $timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของวันอาทิตย์ ต้องอยู่ในช่วง 9.00 - 16.30',
            
                    ]);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30',
            
                    ]);
                }else if($timeRentStart >= $timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30',
            
                    ]);
                }else if($timeRentEnd > $timeEnd){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลาการจองหรือสิ้นสุดการจอง ของ วันจันทร์-ศุกร์ ต้องอยู่ในช่วง 9.00 - 19.30',
            
                    ]);
                }
            }                     
        }                

//------------ validate time > 45 min , <= 120 min , >=now --------------------------------------------------------------------------------------------------------------------//
        $timestart45 = Carbon::parse($timestart);
        $timestart45string = $timestart45->toTimeString();
        $timeend45 = Carbon::parse($timeend);
        $timeend45string = $timeend45->toTimeString();
        if($timestart45->diffInMinutes($timeend45) < 45 ){
            return response()->json([
                'success' => false,
                'message' => 'เวลาการจองของคุณน้อยกว่า 45 นาที',
    
            ]);
        } 
        if($timestart45->diffInMinutes($timeend45) > 120 ){
            if($timeend45string != '00:00:00'){
                return response()->json([
                    'success' => false,
                    'message' => 'เวลาการจองของคุณเกิน 2 ชั่วโมง',
        
                ]);
            }    
        } 

        $timenow = Carbon::now();
        $timenow = $timenow->toTimeString();
        
        $datenow = Carbon::today();
        $datenow = $datenow->toDateString();
        

        if($dateRentStart == $datenow){
            if($timestart45string < $timenow ){
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถจองเวลาในอดีต',
        
                ]);
            }   
        }
          
        

    //------------------------------------------------------------------------------------------------------------------------------//


 //---------------------  validate overlaptime   ---------------------//

        $alldays = Reservation::where('room_id',$roomID)->get();
        

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
                        return response()->json([
                            'success' => false,
                            'message' => 'เวลานี้มีคนจองแล้ว',
                
                        ]);
                    }
                }
                if($timestartinput == $timestart){
                    return response()->json([
                        'success' => false,
                        'message' => 'เวลานี้มีคนจองแล้ว',
            
                    ]);
                }
                if($timestartinput > $timestart){
                    if($timestartinput < $timeEnd){
                        return response()->json([
                            'success' => false,
                            'message' => 'เวลานี้มีคนจองแล้ว',
                
                        ]);
                     }
                }
                if($timeEndinput == '00:00:00' && $timeEnd == '00:00:00'){
                    $timeEndinput = '23:59:00';
                    $timeEnd = '23:59:00';
                    if($timestartinput < $timestart){
                        if($timeEndinput > $timestart){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลานี้มีคนจองแล้ว',
                    
                            ]);
                    }
                    if($timestartinput == $timestart){
                        return response()->json([
                            'success' => false,
                            'message' => 'เวลานี้มีคนจองแล้ว',
                
                        ]);
                    }
                    if($timestartinput > $timestart){
                        if($timestartinput < $timeEnd){
                            return response()->json([
                                'success' => false,
                                'message' => 'เวลานี้มีคนจองแล้ว',
                    
                            ]);
                         }
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

        $UserID = $userID;
        $StudentID = $student_ID;
        $room = $roomID;

        $roomName = Room::find($roomID);
        $roomName = $roomName->RoomName;

        $carbon_date = $request->input('date');
        $timeend = $request->input('timeend');
        $timeend = date('H:i', strtotime($timeend));
        $timeend .=':00';
        
        $carbon_date = date('Y-m-d'.$timeend, strtotime($carbon_date));

        $reservation->ReservationStart = $date;
        $reservation->ReservationEnd = $carbon_date;
        $reservation->user_id = $UserID;
        $reservation->student_id = $StudentID;
        $reservation->room_id = $room;

        if($reservation->save()){
            $admin= User::get()->where('status',1);
            $staff = User::get()->where('status',2);
            $user = User::where('id',$userID)->first();
            Notification::send($admin , new AddReservation($reservation));
            Notification::send($staff , new AddReservation($reservation));
            $today = $reservation->ReservationStart;
            $data = '<font size="2.5"><b>'.$user->Firstname.' '.$user->Lastname.' ('.$user->StudentID.')</b></font> ได้จอง <font size="2.5"><b>'.$reservation->room->RoomName.'</b></font> <br>'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$today->format('d/m/Y').' '.'<font size="3">เวลา '.$reservation->ReservationStart->format('H : i').' - '.$reservation->ReservationEnd->format('H : i')."</font>";
            StreamLabFacades::pushMessage('test','AddReservation',$data);
        }
        

        return response()->json([
            'success' => true,
            'message' => 'การจองสำเร็จ! สามารถดู/ยกเลิก รายการจอง ในเมนู My Reservation',

        ]);
        
    }
    public function roomDetail(Request $request,$roomID){
        $schedules = Reservation::where('room_id',$roomID)->get();
        $ReservedUse = Reservation::where('room_id',$roomID)->where('ActiveStatus',1)->get();
        $res = [];
        foreach($schedules as $schedule){
            $room = Room::where('Roomid',$roomID)->first();
            
            array_push($res,[
                'roomName' => $room->RoomName,
                'roomDescription' => $room->RoomDescription,
                'roomPeople' => $room->RoomPeople,
                'roomImgUrl'=>env('APP_URL','').'/thumbnails'.'/'.$room->ImageName,
                'reservedCount' => count($schedules),
                'reservedUseCount' => count($ReservedUse),
                'dateStart'=>$schedule->ReservationStart->format('d-m-Y'),
                'timeStart'=>$schedule->ReservationStart->format('H:i'),
                'timeEnd'=>$schedule->ReservationEnd->format('H:i'),
                'activeStatus'=>$schedule->ActiveStatus,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'data' => $res
        ]);
        
    }
    public function myReserved(Request $request,$userID){
        $schedules = Reservation::where('user_id',$userID)->get();
        $res = [];
        foreach($schedules as $schedule){
            $room = Room::where('Roomid',$schedule->room_id)->first();
            array_push($res,[
                'roomName' => $room->RoomName,
                'dateStart'=>$schedule->ReservationStart->format('d-m-Y'),
                'timeStart'=>$schedule->ReservationStart->format('H:i'),
                'timeEnd'=>$schedule->ReservationEnd->format('H:i'),
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'data' => $res
        ]);
        
    }
}
