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
                        <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/table.png') }}">&nbsp;&nbsp;ROOM RESERVATION</b></font>
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
            <h2 class="ui left floated header"><font size = "5" color ="#828282">HISTORY</font><br> <font size = "6" color ="#B92000">SEARCH</font></h2>
            <a href ="{{ url('/home') }}">
              <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
            </a>
            <div class="ui clearing divider"></div>
              <form action="{{ url('/historysearch') }}" method="post" class="form-inline">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class ="form-group">
                    <input type ="text" class ="form-control pull-left" name="id" placeholder="รหัสนิสิต" value="{{ isset($id) ? $id: '' }}" id = "border">
                  </div>    
                  <div class ="form-group">
                    <select  class ="form-control pull-left" id = "border" class="drop" name="question">
                        <?php $name ='';             
                        ?>
                        <option value="{{ isset($name) ? $name : ''}}" selected>ทุกห้อง</option>
                        @foreach($room as $roo)
                            @if ($questionId == $roo->Roomid)
                              <option value="{{ isset($roo->Roomid) ? $roo->Roomid : ''}}" selected>{{ $roo->RoomName }}</option>
                            @else
                              <option value="{{ isset($roo->Roomid) ? $roo->Roomid : ''}}">{{ $roo->RoomName }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                              <?php 
                                $id1 = '';
                                $id2 = 'คณะวิศวกรรมศาสตร์ ศรีราชา';
                                $id3 = 'คณะวิทยาการจัดการ';
                                $id4 = 'เศรษฐศาสตร์ ศรีราชา';
                                $id5 = 'วิทยาศาสตร์ ศรีราชา';
                                $id6 = 'วิทยาลัยพาณิชยนาวีนานาชาติ';     
                                $id7 = '-';  
                              ?>
                              <select  class ="form-control pull-left" id = "border" class="drop" name="Faculty">
                                          <option value="{{ isset($id1) ? $id1 : ''}}" @if ($FacultyID == $id1) selected="selected" @endif >ทุกคณะ</option>
                                          <option value="{{ isset($id2) ? $id2 : ''}}" @if ($FacultyID == $id2) selected="selected" @endif>คณะวิศวกรรมศาสตร์ ศรีราชา</option>
                                          <option value="{{ isset($id3) ? $id3 : ''}}" @if ($FacultyID == $id3) selected="selected" @endif>คณะวิทยาการจัดการ</option>
                                          <option value="{{ isset($id4) ? $id4 : ''}}" @if ($FacultyID == $id4) selected="selected" @endif>เศรษฐศาสตร์ ศรีราชา</option>
                                          <option value="{{ isset($id5) ? $id5 : ''}}" @if ($FacultyID == $id5) selected="selected" @endif>วิทยาศาสตร์ ศรีราชา</option>
                                          <option value="{{ isset($id6) ? $id6 : ''}}" @if ($FacultyID == $id6) selected="selected" @endif>วิทยาลัยพาณิชยนาวีนานาชาติ</option>
                                          <option value="{{ isset($id7) ? $id7 : ''}}" @if ($FacultyID == $id7) selected="selected" @endif>เจ้าหน้าที่ห้องสมุด</option>
                              </select>
                  </div>
                  <div class ="form-group">
                    <select  class ="form-control pull-left" id = "border" class="drop" name="day">
                        <option value="{{ isset($id1) ? $id1 : ''}}" @if ($dayID == $id1) selected="selected" @endif >ทุกวัน</option>
                        @for($i = 1;$i<=31;$i++)
                          <option value="{{ isset($i) ? $i : ''}}" @if ($dayID == $i) selected="selected" @endif >{{$i}}</option>
                        @endfor
                    </select>
                  </div>  
                  <div class ="form-group">
                    <select  class ="form-control pull-left" id = "border" class="drop" name="month">
                        <option value="{{ isset($id1) ? $id1 : ''}}" @if ($monthID == $id1) selected="selected" @endif >ทุกเดือน</option>
                        @for($i = 1;$i<=12;$i++)
                          <option value="{{ isset($i) ? $i : ''}}" @if ($monthID == $i) selected="selected" @endif >{{$i}}</option>
                        @endfor
                    </select>
                  </div>  
                  <div class ="form-group">
                    <select  class ="form-control pull-left" id = "border" class="drop" name="year">
                        <option value="{{ isset($id1) ? $id1 : ''}}" @if ($yearID == $id1) selected="selected" @endif >ทุกปี</option>
                        @for($i = $yearnow;$i>$yearnow-5;$i--)
                          <option value="{{ isset($i) ? $i : ''}}" @if ($yearID == $i) selected="selected" @endif >{{$i}}</option>
                        @endfor
                    </select>
                  </div>     
                     <div class ="form-group">      
                        <button class="btn btn-success form-control" type="submit" id = "border">Search</button>
                        <button class="btn btn-danger form-control" type="submit" name ="excelButton" id = "border" value="excel">Download Excel</button>
                    </div>
               </form>
               <br>
               <br>
               <h2 class="ui left floated header"><font size = "6" color ="#B92000">HISTORY</font><br> <font size = "5" color ="#828282">{{$Reservations->total()}} ITEMS <font size = "4" color ="#828282">({{$diff}} Hours {{$min}} Minutes)</font></font></h2>
               <div class="ui clearing divider"></div>
                @if(count($Reservations) > 0)
                <div class="table-responsive table-inverse" id = "table">
                  <table class="table table-bordered" id ="border">
                    <tr >
                    <thead>
                      <th class="bg-primary">Student ID</th>
                      <th class="bg-primary">Firstname</th>
                      <th class="bg-primary">Lastname</th>
                      <th class="bg-primary">Faculty</th>
                      <th class="bg-primary">Room</th>
                      <th class="bg-primary">Date</th>
                      <th class="bg-primary">Use Time</th>
                    </tr>
                    </thead>                 
                    @foreach($Reservations as $Reservation)
                       <tbody>
                          <tr>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->StudentID }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->FirstName }}</font></td>
                               <td id ="tablecolor"<font size ="3">{{ $Reservation->LastName }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->Faculty }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->RoomName }}</font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('d/m/Y') }} </font></td>
                               <td id ="tablecolor"><font size ="3">{{ $Reservation->ReservationStart->format('H : i') }} - {{ $Reservation->ReservationEnd->format('H : i') }}</font></font></td>
                          </tr>
                        </tbody>
                    @endforeach
                 </table>        
                </div>
                <span class ="pull-right">{{ $Reservations->appends(Request::except('page'))->links() }} </span>              
             @else
             		<div class="table-responsive table-inverse" id="table">
                  <table class="table table-bordered" id = "border">
                    <tr>
                    <thead>
                      <th class="danger"><center><font color ="#383838" size = "3">....ไม่มีข้อมูล....</font></center></th>
                    </tr>
                    </thead>  
                  </table>
                </div>
                <br>
             @endif

          </div>
        </div>
        <br>  
        <br>  
@endsection
