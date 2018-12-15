@extends('layouts.app')

@section('title')
<title>Library Reservation</title>
@endsection

@section('navbar')
<audio id="audio" src="{{ asset('/sound/noti.mp3') }}" autostart="false" ></audio>
<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container" id ="bar">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href = "{{ url('/home') }}">
                        <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/table.png') }}">&nbsp;&nbsp;LIBRARY RESERVATION</b></font>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/') }}"><i class="home icon"></i>Home</a></li>
                            <li><a href="{{ url('/adminindex') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a href="{{ url('/') }}"><i class="home icon"></i><b>Home</b></a></li>
                            @if($admin)
                              <li class="dropdown" id ="dropdownz">
                                  <a href="#" class="dropdown-toggle" id ="notification" data-toggle="dropdown" role="button" aria-expanded="false">
                                      <i class="alarm icon"></i>&nbsp;<b>Notifications</b> <span class="badge danger" id="count">{{ count(auth()->user()->unreadnotifications) }}</span>
                                  </a>

                                  <ul class="dropdown-menu scrollable-menu" role="menu" id ="showNotification">
                                  @if(count(auth()->user()->notifications) >5)
                                        <?php $time = 0 ?>
                                        @foreach(auth()->user()->notifications as $note)
                                            <li>
                                                @if($time == 10)
                                                  @break
                                                @endif
                                                <a href="{{ url('/reservationsearch') }}" class ="{{$note->read_at == null ? 'unread' : ''}}">
                                                    <img width ="10" height ="10" src="{{ asset('/seimg/jood.png') }}">&nbsp;{!! $note->data['data'] !!}
                                                </a>
                                            </li>
                                            
                                            <?php $time+=1 ?>
                                        @endforeach
                                  @else
                                      @if(count(auth()->user()->notifications) >0)
                                        @foreach(auth()->user()->notifications as $note)
                                              <li>
                                                  <a href="{{ url('/reservationsearch') }}"  class ="{{$note->read_at == null ? 'unread' : ''}}">
                                                      <img width ="10" height ="10" src="{{ asset('/seimg/jood.png') }}">&nbsp;{!! $note->data['data'] !!}
                                                  </a>

                                              </li>
                      
                                        @endforeach
                                      @else
                                          <li>
                                              <a href="#">ไม่มีการแจ้งเตือน</a>
                                          </li>  
                                      @endif
                                  @endif
                                  </ul>
                              </li>
                           @endif

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="user icon"></i>
                                    @if(Auth::user()->status == 0) 
                                        <b>{{ Auth::user()->Firstname }} {{ Auth::user()->Lastname }} &nbsp;</b><span class="caret"></span>
                                    @elseif(Auth::user()->status == 2)
                                        <b>เจ้าหน้าที่ห้องสมุด</b>&nbsp;<span class="caret"></span>
                                    @elseif(Auth::user()->status == 1)
                                        <b>ผู้ดูแลระบบ</b>&nbsp;<span class="caret"></span>
                                    @endif
                                </a>    
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/user/manage/'.Crypt::encrypt(Auth::user()->id)) }}">
                                          <img width ="23" height ="22" src="{{ asset('/seimg/setting2.png') }}">&nbsp;&nbsp;Manage Account
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <img width ="23" height ="23" src="{{ asset('/seimg/logout.png') }}">&nbsp;&nbsp;Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>

                             
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
@endsection

@section('content')
<div class="container" id ="allmenu">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <br>
          <br>
          <br>
          <br>
          <h2 class="ui left floated header"><font size = "5" color ="#828282">SEARCH</font><br><font size = "6" color ="#B92000">RESERVATION</font></h2>
          @if($onadmin == 0)
            <a href ="{{ url('/home') }}">
              <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
            </a>
          @else
            <a href ="{{ url('/adminindex') }}">
            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
          </a>
          @endif
          <div class="ui clearing divider"></div>
              <form action="{{ url('/reservationsearch') }}" method="get" class="form-inline">
                  <div class ="form-group">
                    <input type ="text" class ="form-control " name="id" placeholder="รหัสนิสิต" value="{{ isset($id) ? $id: '' }}" id = "border">
                  </div>     
                  <div class ="form-group">
                    <select  class ="form-control" id="border" class="drop" name="question">
                        <?php $name ='';             
                        ?>
                        <option value="{{ isset($id1) ? $id1 : ''}}" selected>{{ $name }}ทุกห้อง</option>
                        @foreach($room as $roo)
                            @if ($questionId == $roo->Roomid)
                              <option value="{{ isset($roo->Roomid) ? $roo->Roomid : ''}}" selected>{{ $roo->RoomName }}</option>
                            @else
                              <option value="{{ isset($roo->Roomid) ? $roo->Roomid : ''}}">{{ $roo->RoomName }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>   
                  <div class ="form-group">
                      <button class="btn btn-success form-control" id = "border" type="submit">Search</button>
                  </div>
               </form>
               <br>
               <br>
               <h2 class="ui left floated header"><font size = "6" color ="#B92000">ITEMS</font><br><font size = "5" color ="#828282">RESERVATION</font></h2>
               <div class="ui clearing divider"></div>
                @if(count($Reservations) > 0)
                  @if(Session::has('flash_message'))
                      <div class="alert alert-danger"><em> <center><li>{!! session('flash_message') !!}</li></center></em></div>
                  @endif
                  @if(Session::has('flash_message2'))
                      <div class="alert alert-success"><em> <center><li>{!! session('flash_message2') !!}</li></center></em></div>
                  @endif
                  <div class="table-responsive table-inverse" id = "table">
                    <table class="table table-bordered" id ="border">
                      <tr>
                      <thead>
                        <th class="bg-primary">Student ID</th>
                        <th class="bg-primary">Firstname</th>
                        <th class="bg-primary">Phone</th>
                        <th class="bg-primary">Room</th>
                        <th class="bg-primary">Date</th>
                        <th class="bg-primary">Use Time</th>
                        <th class="bg-primary">Status</th>
                        <th class="bg-primary">Manage</th>
                        <th class="bg-primary">Add Time</th>
                      </tr>
                      </thead>                 
                    @foreach($Reservations as $Reservation)
                       <tbody>
                          <tr>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->user->StudentID }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->user->Firstname }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->user->Phone }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->room->RoomName }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('d/m/Y') }} </font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('H : i') }} - {{ $Reservation->ReservationEnd->format('H : i') }}</font></font></td>
                               <td id ="tablecolor">
                                @if($Reservation->ActiveStatus == 1)
                                  <img width ="12" height ="12" src="{{ asset('/seimg/circleactive.png') }}">&nbsp;<font size ="3">Active</font>
                                @else
                                  <img width ="12" height ="12" src="{{ asset('/seimg/circlewaiting.png') }}">&nbsp;<font size ="3">Waiting</font>
                                @endif


                               </td>
                               <td id ="tablecolor">
                                 @if($Reservation->ActiveStatus != 1)
                                   <form style="margin-left:4px;display:inline;" action="{{ url('/reservation/'.$Reservation->ReservationID) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="image" src="{{ asset('/seimg/check.png') }}" width ="30" height ="28" >
                                    </form>
                                 
                                 <form style="display:inline;margin-left:-9px;" action="{{ url('/reservation/'.$Reservation->ReservationID.'/search') }}" method="post" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    &nbsp;&nbsp;<input type="image" src="{{ asset('/seimg/delete.ico') }}" width ="30" height ="28">
                                  </form>
                                </td>
                                @else
                                    <form style="display:inline" action="{{ url('/reservation/'.$Reservation->ReservationID.'/search') }}" method="post" onsubmit="return confirm('Are you sure?')">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="_method" value="DELETE">
                                      <center><input type="image" src="{{ asset('/seimg/delete.ico') }}" width ="30" height ="28"></center>
                                    </form>
                                @endif
                                 <td id ="tablecolor">
                                    <form class="pull-left" action="{{ url('/add/'.$Reservation->ReservationID) }}" method="get">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button style="width:70px;" type = "submit" class="ui teal button"><font size = "2" >ต่อเวลา</font></button>
                                    </form>

                                 </td>

                          </tr>
                        </tbody>
                    @endforeach
                 </table>        
                </div>
                <span class ="pull-right">{{ $Reservations->appends(Request::except('page'))->links() }} </span>
               
                
             @else
             		<div class="table-responsive table-inverse" id="table">
                  <table class="table table-bordered" id ="border">
                    <tr>
                    <thead>
                      <th class="bg-danger"><center><font color ="#383838" size = "3">....ไม่มีข้อมูล....</font></center></th>
                    </tr>
                    </thead>  
                  </table>
                </div>
             @endif
             <br>
             <br>
          </div>
        </div>
@endsection
