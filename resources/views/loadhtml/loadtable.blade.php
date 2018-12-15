@if(count($Reservations) >0)
  <table class="table table-bordered" id ="border">
            <tr>
            <thead>
                   <th class="bg-primary">Student ID</th>
                   <th class="bg-primary">Firstname</th>
                   <th class="bg-primary">Lastname</th>
                   <th class="bg-primary">Room</th>
                   <th class="bg-primary">Time Start</th>
                   <th class="bg-primary">Time End</th>
            </tr>
            </thead>                 
       @foreach($Reservations as $Reservation)
        <tbody id = "tableupdate">
          <tr>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->user->StudentID }}</font></td>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->user->Firstname }}</font></td>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->user->Lastname }}</font></td>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->room->RoomName }}</font></td>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('H : i') }}</font></font></td>
              <td id = "tablecolor"><font size ="3">{{ $Reservation->ReservationEnd->format('H : i') }}</font></td>

          </tr>
        </tbody>
        @endforeach
  </table>


@else
    <table class="table table-bordered" id = "border">
                <tr>
                <thead>
                     <td class="bg-danger"><center><font color ="black"><b>สถานะ : <font color="#B72202">ไม่มีการจอง</b></font></font></center></th>
                </tr>
                </thead>      
    </table>
@endif    
