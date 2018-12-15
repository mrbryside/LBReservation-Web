<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Carbon\Carbon;
use Mail;
use App\Ban;
use App\Reservation;
use App\Usermodel;
use App\Reservationstore;

class CronJobController extends Controller
{
    public function index(){
        $migrate = \Artisan::call('migrate');
        \Artisan::call('db:seed');
        return 'bank';
    }
    public function schedule1(){
            $reser = Reservation::where('ReservationStart', '<=', Carbon::now())->get();
            $users = Usermodel::get();
            $email = '';
            $room = '';
            $start ='';
            $end = '';
            foreach($reser as $rese){
                foreach($users as $user){
                    if($rese->ReservationEnd > Carbon::now()){
                        if($user->id == $rese->user_id){
                            $email = $user->email;
                            $room = $rese->room->RoomName;
                            $start = $rese->ReservationStart;
                            $end = $rese->ReservationEnd;
                            if($rese->emailStatus == 0){
                                \DB::table('reservations')->where('ReservationID', '=', $rese->ReservationID)->update(['emailStatus'=>1]);
                                $data = array( 'email' => $email, 'room' => $room, 'start' => $start, 'end' => $end );
                                Mail::raw('', function ($message) use ($data){
                                    $message->to($data['email']);
                                    $message->from('libraryreservsrc@gmail.com', 'Library Reservation');
                                    $message->setBody('คุณได้จอง '.$data['room'].' วันนี้ เวลา '.$data['start']->format('H : i').' - '.$data['end']->format('H : i').' เวลาการจองของคุณได้เริ่มขึ้นแล้ว หากคุณไม่มายืนยันการใช้งาน ภายใน 15 นาที ระบบจะยกเลิกสิทธิ์ทันที (หากถูกตัดสิทธิ์ครบ3ครั้งจะถูกระงับการจอง 1 สัปดาห์)');
                                    $message->subject('แจ้งเตือน:ถึงเวลาใช้งาน');
                                });  
                            }           
                             
                        }                             
                    }
                }
            }

        $reser = Reservation::get();
        $users = Usermodel::get();
        foreach($reser as $rese){
            $reservation = Carbon::parse($rese->ReservationStart);
            $start = $reservation->addMinutes(15);
            if($start <= Carbon::now()){
                if($rese->ActiveStatus == 0){
                    Reservation::where('ReservationID', '=', $rese->ReservationID)->delete();
                    foreach($users as $user){
                        if($user->id == $rese->user_id){
                            $email = $user->email;
                            $room = $rese->room->RoomName;
                            $start = $rese->ReservationStart;
                            $end = $rese->ReservationEnd;    
                            $countban = $user->CountBan += 1;
                            \DB::table('users')->where('id', '=', $user->id)->update(['CountBan'=>$countban]);

                            // $reservationstores = Reservationstore::get();
                            // $storeID = 0;
                            // foreach($reservationstores as $reservationstore){
                            //     if($rese->ReservationStart == $reservationstore->ReservationStart && $reservationstore->RoomName == $rese->room->RoomName){
                            //         if($rese->user->StudentID == $reservationstore->StudentID){
                            //             $storeID = $reservationstore->ReservationID;
                            //             \DB::table('reservationstores')->where('ReservationID','=',$storeID)->delete();
                            //         }
                            //     }
                            // }
                            
                            $data = array( 'email' => $email, 'room' => $room, 'start' => $start, 'end' => $end );
                            Mail::raw('', function ($message) use ($data){
                                $message->to($data['email']);
                                $message->from('libraryreservsrc@gmail.com', 'Library Reservation');
                                $message->setBody('รายการจอง '.$data['room'].' วันนี้ เวลา '.$data['start']->format('H : i').' - '.$data['end']->format('H : i').' ได้ถูกยกเลิกแล้ว เนื่องจากท่านไม่มายืนยันสิทธิ์ ภายใน 15 นาที หลังเวลาเริ่ม (หากถูกตัดสิทธิ์ครบ 3 ครั้งจะถูกระงับการจอง 1 สัปดาห์)');
                                $message->subject('แจ้งเตือน:ยกเลิกการจอง');
                            });  
                        }
                    }
                }
            }
        }
        $userscheck = Usermodel::get();
        foreach($userscheck as $user){
            $userinBan = Ban::where('user_id',$user->id)->first();
            if($user->CountBan >= 3 && $userinBan == null && $user->status == 0){
                \DB::table('bans')->insert(
                    ['user_id' => $user->id,
                     'created_at' =>  \Carbon\Carbon::now(), 
                     'updated_at' => \Carbon\Carbon::now()]
                );
                $email = $user->email;
                $data = array( 'email' => $email);
                Mail::raw('', function ($message) use ($data){
                    $message->to($data['email']);
                    $message->from('libraryreservsrc@gmail.com', 'Library Reservation');
                    $message->setBody('ท่านถูกระงับการจองเป็นเวลา 1 สัปดาห์ เนื่องจาก จองห้องศึกษา แล้วไม่มายืนยันสิทธิ์ ครบ 3 ครั้ง');
                    $message->setBody('Tips:ท่านสามารถยกเลิกรายการจองของท่านได้ เพื่อไม่ให้ถูกระงับการจองในครั้งหน้า');
                    $message->subject('แจ้งเตือน:ถูกระงับการจอง');
                });  
            }
        }

        

        
    }
    public function schedule2(){
        $reservations = Reservation::get();
        foreach($reservations as $reservation){
            if($reservation->ReservationEnd <= Carbon::now()){
                $reservationstore = new Reservationstore;
                $reservationstore->ReservationStart = $reservation->ReservationStart;
                $reservationstore->ReservationEnd = $reservation->ReservationEnd;
                $reservationstore->StudentID = $reservation->user->StudentID;
                $reservationstore->RoomName = $reservation->room->RoomName;
                $reservationstore->FirstName = $reservation->user->Firstname;
                $reservationstore->LastName = $reservation->user->Lastname;
                $reservationstore->Faculty = $reservation->user->Faculty;
                $reservationstore->save();

                $reservation->delete();
            }
        }
    }
}
