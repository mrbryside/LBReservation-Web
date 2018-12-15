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
          <h2 class="ui left floated header"><font id="yourtext" size = "5" color ="#828282">YOUR</font><br> <font id="reservetext" size = "6" color ="#B92000">RESERVED</font></h2>
          <a href ="{{ url('/home') }}">
            <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
          </a>
          <div class="ui clearing divider"></div>
            <div class="ui styled fluid accordion" id ="border">
              <div class="title">
                <i class="dropdown icon"></i>
                 <font color ="#AC2002">เปลี่ยนรายการจองของท่าน ?</font>
              </div>
              <div class="content">
                <font size ="3">
                  <ol>
                    <span id ="textrule">
                      <li>ให้ทำการยกเลิกรายการจองเดิม ก่อนทำการจองห้องใหม่</li>
                      <li>การยกเลิก รายการจองไม่สามารถทำในเวลา 15 นาทีก่อนเวลาใช้งาน</li>
                      <li>หากยังไม่สิ้นสุดเวลาใช้งานรอบปัจจุบัน จะไม่สามารถจองห้องใหม่ได้</li>
                      <li>การลบรายการจองในเมนูนี้ จะไม่ถูกนับในการถูกระงับการจอง</li>
                      
                    </span>
                  </ol>
                </font>
              </div>
            </div>
            <br>
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
                        <th class="bg-primary">Room</th>
                        <th class="bg-primary">Date</th>
                        <th class="bg-primary">Use Time</th>
                        <th class="bg-primary">Manage</th>
                      </tr>
                      </thead>                 
                    
                    @foreach($Reservations as $Reservation)
                       <tbody>
                          <tr>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->room->RoomName }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('d/m/Y') }} </font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('H : i') }} - {{ $Reservation->ReservationEnd->format('H : i') }}</font></font></td>
                               <td id ="tablecolor"><form action="{{ url('/reservation/'.$Reservation->ReservationID.'/myreserve') }}" method="post" @if(auth()->user()->status == 0) onsubmit="return confirm('ก่อนดำเนินการใดๆ ควรอ่านกฏการจองห้องในหน้าแรกก่อน คุณพร้อมแล้วใช่ไหม?')" @else  onsubmit="return confirm('คุณแน่ใจแล้วใช่ไหม?')"  @endif>
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <center><input type="image" src="{{ asset('/seimg/delete.ico') }}" width ="35" height ="30"></center>
                                </form></td>
                          </tr>
                        </tbody>
                    @endforeach
                 </table>               
              </div>	                    
            @else
           		<div class="table-responsive table-inverse" id="table">
                <table class="table table-bordered" id = "border">
                  <tr>
                  <thead>
                    <th class="bg-danger"><center><font color ="#383838" style="font-size:14px;">....ไม่มีข้อมูลการจองของท่าน....</font></center></th>
                  </tr>
                  </thead>  
                </table>
              </div>
           @endif
           @if(count($Reservations) != 0)
                <font color = "#5B5B5B" id = "phonetext"><b>Tips : </b>สามารถเลื่อนตารางเพื่อดูเพิ่มเติม </font>
           @endif
        </div>
      </div>
@endsection

@section('css')
  @media(max-width:767px){
        #yourtext{
          font-size:18px !important;
        }
        #reservetext{
          font-size:25px !important;
        }
        #backtext{
          font-size:14px !important;
        }
    }
@endsection

@section('script')

  $('.ui.accordion')
    .accordion()
  ;
@endsection
