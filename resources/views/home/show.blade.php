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
          <h2 class="ui left floated header"><font id="statustext" size = "6" color ="#B92000">STATUS</font><br> <font id="roomnametext" size = "5" color ="#828282">{{ $Room->RoomName }}</font></h2>
          @if($Room->RoomPeople > 1)
            <a href="{{ url('/home') }}">
              <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
            </a>
          @elseif($Room->RoomPeople == 1)
            @if($admin == 0)
              <a href="{{ url('/homesingleuser') }}">
                <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
            @else
              <a href="{{ url('/single') }}">
                <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
            @endif
          @endif
          <div class="ui clearing divider"></div>   
              <div class="ui styled fluid accordion" id ="border">
                <div class="title">
                  <i class="dropdown icon"></i>
                   <font color ="#AC2002">จองห้องศึกษาอย่างไร ?</font>
                </div>
                <div class="content">
                      <font size ="3">
                        <ol>
                          <span id ="texthowto">
                            <li>พิจารณาสถานะห้องจากตารางรายการจองด้านล่าง เพื่อเลือกจองในวันเวลาที่ห้องว่าง</li>
                            <li>เวลาการจองห้องต้องอยู่ในช่วง 45 นาที - 2 ชั่วโมง และสามารถจองล่วงหน้าได้ 1 วันเท่านั้น</li>
                            <li>หากมีรายการจองระบบจะแสดง วันที่/เวลาการใช้งาน ทุกรายการ</li>                
                            <li>รายการจองที่พื้นหลังเป็นสีเหลือง/มีดอกจัน คือรายการจองของท่าน</li>

                          </span>
                        </ol>
                      </font>
                </div>
               </div>
                <br>       
                @if(count($Reservations) > 0)
                  <div class="table-responsive table-inverse" id ="table">
                    <table class="table table-bordered" id="border">
                      <tr>
                      <thead>
                        <th class="bg-primary">Date</th>
                        <th class="bg-primary">Use Time</th>
                        <th class="bg-primary">Status</th>
                      </tr>
                      </thead>                 
                      @foreach($Reservations as $Reservation)
                         <tbody>
                            <tr>
                                 <td @if(auth()->user()->id != $Reservation->user_id) id ="tablecolor" @else class ="bg-warning"  @endif><font size ="3">{{ $Reservation->ReservationStart->format('d/m/Y') }} @if(auth()->user()->id == $Reservation->user_id)<font color ="red" >** </font>@endif </font></td>
                                 <td @if(auth()->user()->id != $Reservation->user_id) id ="tablecolor" @else class ="bg-warning"  @endif><font size ="3">{{ $Reservation->ReservationStart->format('H : i') }} - {{ $Reservation->ReservationEnd->format('H : i') }}</font></font></td>
                                 <td @if(auth()->user()->id != $Reservation->user_id) id ="tablecolor" @else class ="bg-warning"  @endif>
                                    @if($Reservation->ActiveStatus == 1)
                                      <img width ="12" height ="12" src="{{ asset('/seimg/circleactive.png') }}">&nbsp;<font size ="3" color="green">กำลังใช้งาน</font>
                                    @else
                                      <img width ="12" height ="12" src="{{ asset('/seimg/circlewaiting.png') }}">&nbsp;<font size ="3" color="red">รอใช้งาน</font>
                                    @endif
                                 </td>

                            </tr>
                          </tbody>
                      @endforeach
                   </table>
                  </div>
                  <span class ="pull-right">{{ $Reservations->links() }} </span>
                @else
                  <table class="table table-bordered">
                    <tr>
                    <thead>
                         <td class="bg-danger" id="border"><center><font color ="black"><b>สถานะ : <font color="#B72202">ไม่มีการจอง</b></font></font></center></th>
                    </tr>
                    </thead>      
                  </table>
                @endif  
                @if(count($Reservations) > 0)
                  <font color = "#5B5B5B" id = "phonetext"><b>Tips : </b>สามารถเลื่อนตาราง ซ้าย-ขวา ได้ </font>
                @endif
          </div>
        </div>
      </div>
      <div class="container" id ="form">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                        <br>
                        <br>
                        <h2 class="ui left floated header">
                        <font id="formtext" size = "6" color ="#B92000">FORM</font><br> <font id="reservetext" size = "5" color ="#828282">RESERVATION</font>
                        </h2>
                        @if(auth()->user()->status != 0)
                          <a href ="#/" id ="showadmin">
                            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B" id="textadmin">เจ้าหน้าที่จองใช้งาน<i class="arrow right icon"></i></font></h2>
                          </a>
                          <a href ="#/" id ="shownisit">
                            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B" id ="textnisit">เจ้าหน้าที่จองให้นิสิต<i class="arrow right icon"></i></font></h2>
                          </a>
                        @endif
                    <div class="ui clearing divider"></div>
                      <div class="ui raised segment">
                        <br>
                        <font size ="3">
                          @if(Session::has('flash_message'))
                              <div class="alert alert-danger" id ="message"><em> <center><li>{!! session('flash_message') !!}</li></center></em></div></center>
                          @endif
                          @if(Session::has('flash_message2'))
                              <div class="alert alert-info" id ="message2"><em><center><li>{!! session('flash_message2') !!}</li></center></em></div></center>
                          @endif
                          @if(count($errors)>0)
                              @foreach($errors->all() as $error)
                                <div class="alert alert-danger" id ="message3"><em><center><li>{{$error}}</li></center></em></div>
                              @endforeach
                          @endif
                        </font>
                        <div id ="textrule">
                            <center><h2><i style ="font-size:0.8em;"class="warning circle icon"></i><font id = "finishReserve">ข้อปฏิบัติเมื่อจองห้องเสร็จแล้ว</font></h2></center>
                            <font size ="3">
                              <br>
                              <ul id="ruletextall">
                                <li>ท่านต้องมายืนยันการใช้งานด้วยตนเอง</li>
                                <li>ยืนยันกับเจ้าหน้าที่ประจำเคาน์เตอร์ให้บริการ โดยใช้บัตรนิสิต</li>
                                <li>มีเวลายืนยัน 15 นาทีหลังเวลาจองเริ่มต้น</li>
                                <li>หากไม่ได้ยืนยัน จะถูกยกเลิกสิทธิ์อัตโนมัติ</li>
                              </ul>
                            </font>
                            <center>
                            <button class ="btn btn-primary" id ="jong"><font size ="2"><i class="sign in icon"></i>จองห้อง</font></button></center>
                        </div>
                        <form class="form-horizontal" action="{{ url('/reservation/add/'.$Room->Roomid) }}" enctype="multipart/form-data" method="post" @if(auth()->user()->status == 0) onsubmit="return confirm('ก่อนดำเนินการใดๆ ควรอ่านกฏการจองห้องในหน้าแรกก่อน คุณพร้อมแล้วใช่ไหม?')" @else onsubmit="return confirm('คุณแน่ใจแล้วใช่ไหม?')" @endif  id = "reservationform">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">
                                <font size ="3">
                                  <div class="form-group" id ="studentForm">
                                      @if(auth()->user()->status != 0)

                                        <label class="col-md-5 control-label">Student ID<font color ="red">**</font></label>
                                      
                                        <div class="col-md-3">
                                              <input type='text' id ="studentID" name ="StudentID" class="form-control" placeholder = "รหัสนิสิต" required>
                                        </div>

                                      @else
                                            <input type='hidden' id ="studentID" name ="StudentID" value ="{{ auth()->user()->StudentID }}" class="form-control">

                                      @endif
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Date<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='t3ext' name ="date" class="form-control" id='datetimepicker1' placeholder = "วันที่" required>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Time Start<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="timestart" class="form-control" id='datetimepicker2' placeholder = "เวลาเริ่มต้น" required>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Time End<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="timeend" class="form-control" id='datetimepicker3' placeholder = "เวลาสิ้นสุด" required>
                                      </div>
                                  </div>

                                </font>
                              <div class="form-group">
                                      <div class="col-md-5 col-md-offset-5">
                                          <button type="submit" class="btn btn-primary" class="form-group">
                                          <i class="write icon"></i>Submit</button>
                                      </div>         
                              </div>
                        </form>
                        <br>
                    </div>
                    <br>
                    <br>
          </div>
      </div>
@endsection

@section('css')
  @media(max-width:767px){
        #statustext{
          font-size:25px !important;
        }
        #roomnametext{
          font-size:18px !important;
        }
        #backtext{
          font-size:14px !important;
        }
        #formtext{
          font-size:25px !important;
        }
        #reservetext{
          font-size:20px !important;
        }
        #ruletextall{
          font-size:14px !important;
        }
    }
@endsection

@section('script')
  $('#shownisit').hide();
  $('#shownadmin').hide();
  $('#textadmin').hide();
  $('#textnisit').hide();

  $('#reservationform').hide();
  $('#jong').on('click', function(){
    $('#textrule').hide();
    $('#message').hide();
    $('#message2').hide();
    $('#message3').hide();
    $('#reservationform').transition('scale');
    $('#showadmin').fadeIn();
    $('#textadmin').fadeIn();
  });
  $('#showadmin').on('click', function(){
    $('#shownisit').fadeIn();
    $('#showadmin').hide();
    $('#textadmin').hide();
    $('#textnisit').fadeIn();
    $('#studentForm').fadeOut("fast");
    $("#studentID").val("myself");
  });
  $('#shownisit').on('click', function(){
    $('#showadmin').fadeIn();
    $('#shownisit').hide();
    $('#textadmin').fadeIn();
    $('#textnisit').hide();
    $('#studentForm').fadeIn();
    $("#studentID").val("");
  });

  $('.ui.accordion')
    .accordion()
  ;
@endsection



@section('loadtable')
  var date = new Date();
  date.setHours(0,0,0,0);
  $(function () {
             $('#datetimepicker1').datetimepicker({
                format: 'DD-MM-YYYY'
            });
        });
        $(function () {
             $('#datetimepicker2').datetimepicker({
                format: 'HH:mm',
                useCurrent: 'day'
            });
        });
        $(function () {
             $('#datetimepicker3').datetimepicker({
                format: 'HH:mm',
                useCurrent: 'day'
            });
        });


@endsection