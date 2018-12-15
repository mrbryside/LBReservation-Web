<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use App\Reservation;
use App\Usermodel;
class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

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
           
    }
}
