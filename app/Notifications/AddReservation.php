<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Reservation;
use Carbon\Carbon;

class AddReservation extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $today = $this->reservation->ReservationStart;
        if(auth()->user()->id != $this->reservation->user_id){
            return [
                
                    'data' => '<font size="2.5"><b>'.auth()->user()->Firstname.' '.auth()->user()->Lastname.' ('.auth()->user()->StudentID.')</b></font> ได้จอง(ให้นิสิต) <font size="2.5"><b>'.$this->reservation->room->RoomName.'</b></font> <br>'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$today->format('d/m/Y').' '.'<font size="3">เวลา '.$this->reservation->ReservationStart->format('H : i').' - '.$this->reservation->ReservationEnd->format('H : i')."</font>",
                    'id' => $this->reservation->room_id,
                
            ];
        }
        else{
            return [
                
                    'data' => '<font size="2.5"><b>'.auth()->user()->Firstname.' '.auth()->user()->Lastname.' ('.auth()->user()->StudentID.')</b></font> ได้จอง <font size="2.5"><b>'.$this->reservation->room->RoomName.'</b></font> <br>'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$today->format('d/m/Y').' '.'<font size="3">เวลา '.$this->reservation->ReservationStart->format('H : i').' - '.$this->reservation->ReservationEnd->format('H : i')."</font>",
                    'id' => $this->reservation->room_id,
            ];
        }
    }
}
