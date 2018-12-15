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
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <img width ="25" height ="25" src="{{ asset('/seimg/logout.png') }}">&nbsp;&nbsp;Logout
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

<div class="ui modal" id ="rulemodal" style="overflow-y: scroll;
    padding-right: 0 !important;">
  <div class="header"><font size ="3" color ="#711400"><i class="announcement icon"></i>ข้อปฏิบัติ (สมาชิกใหม่ต้องอ่าน)</font></div>
  <div class="scrolling content">
    <font size ="3">
      <p> 
        <b>
          <span>1) สามารถจองได้เพียง 1 ห้องเท่านั้น เมื่อใช้ห้องเสร็จแล้ว จึงจะสามารถจองต่อได้</span>
          <br>
          <span>2) เวลาการจองห้องต้องอยู่ในช่วง 45 นาที - 2 ชั่วโมง และสามารถจองล่วงหน้าได้ 1 วันเท่านั้น</span>
          <br>
          <span>3) กรุณาตรวจสอบจำนวนผู้ใช้งานขั้นต่ำของแต่ละห้อง หากไม่ถึงกำหนดจะถูกตัดสิทธิ์ตอนเข้าใช้งาน</span>
          <br>
          <span>4) เมื่อทำการจองห้องแล้ว ต้องมายืนยันสิทธิ์กับเจ้าหน้าที่โดยใช้บัตรนิสิต</span>
          <br>
          <span>5) หากไม่มายืนยันการจองภายในเวลา 15 นาทีของเวลาที่เริ่มใช้งาน ระบบจะยกเลิกการจองอัตโนมัติ</span>
          <br>
          <span>6) ระบบจะส่งอีเมลแจ้งเตือนเมื่อถึงเวลาใช้งาน และยกเลิกการใข้งานกรณีไม่ได้ยืนยันสิทธิ์</span>
          <br>
          <span>7) หากถูกตัดสิทธิ์ครบ 3 ครั้ง ท่านจะถูกระงับการจองเป็นเวลา 1 สัปดาห์</span>
          <br>
          <span>8) ท่านสามารถยกเลิก / แก้ไข การจองของตนเองได้ที่เมนู My Reservation</span>
          <br>
          <span>9) เปิดให้บริการ วันจันทร์-ศุกร์ เวลา 9.00-19.30 น. และ วันเสาร์-อาทิตย์ เวลา 9.00-16.30 น.</span>
          <br>
          <span>10) ช่วงสอบจะขยายเวลาเปิดให้บริการเฉพาะห้องศึกษากลุ่ม ชั้น 2 ในวันจันทร์-ศุกร์ เวลา 9.00-22.00 น. และ วันเสาร์-อาทิตย์ เวลา 9.00-18.30 น.</span>
        <br>
        <br>
        <span class ="pull-right" ><font color ="#711400">LIBRARY RESERVATION </font></span>
        </b>
      </p>
    </font>
    <br>
    <center>
    <div class="ui clearing divider"></div>
    <a id ="hidemodal" class = "btn btn-primary"><font size ="2"><i class="reply icon"></i>เข้าใจแล้ว</font></a>
    </center>
    <br>
  </div>
</div>

<div class="container" id ="allmenu">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
              <br>
              <br>
              <br>
              <br>
              <h2 class="ui left floated header"><font size = "6" color ="#B92000">FORM</font><br> <font size = "5" color ="#828282">INFORMATION</font></h2>
              <a href ="{{ url('/') }}" >
                <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
            <div class="ui clearing divider"></div>
                <div class="ui raised segment">
                  <br>
                  <div class ="alert alert-info"><center><font size = "3">** โปรดกรอกข้อมูลให้ครบตามจริง ข้อมูลนี้จะมีผลในการจองห้อง **</font></center></div>
      						<form class="form-horizontal" action="{{ url('/writeuser/'.auth()->user()->id) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
      							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <font size ="3">
                       <div class="form-group{{ $errors->has('StudentID') ? ' has-error' : '' }}">
                          <label for="StudentID" class="col-md-4 control-label">StudentID<font color ="red">**</font></label>

                          <div class="col-md-6">
                              <input id="StudentID" type="text" class="form-control" name="StudentID" value="{{ old('StudentID') }}" required autofocus>

                              @if ($errors->has('StudentID'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('StudentID') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
        							 <div class="form-group">
                                  <label for="Faculty" class="col-md-4 control-label">Faculty<font color ="red">**</font></label>
                                  <div class="col-md-6">
                                      <select  class ="form-control" id="Faculty" class="drop" name="Faculty" required>
                                                  <option value="" selected disabled>Please select</option>
                                                  <option value="คณะวิศวกรรมศาสตร์ ศรีราชา" @if (old('Faculty') == 'คณะวิศวกรรมศาสตร์ ศรีราชา') selected="selected" @endif>คณะวิศวกรรมศาสตร์ ศรีราชา</option>
                                                  <option value="คณะวิทยาการจัดการ" @if (old('Faculty') == 'คณะวิทยาการจัดการ') selected="selected" @endif>คณะวิทยาการจัดการ</option>
                                                  <option value="เศรษฐศาสตร์ ศรีราชา" @if (old('Faculty') == 'เศรษฐศาสตร์ ศรีราชา') selected="selected" @endif>เศรษฐศาสตร์ ศรีราชา</option>
                                                  <option value="วิทยาศาสตร์ ศรีราชา" @if (old('Faculty') == 'วิทยาศาสตร์ ศรีราชา') selected="selected" @endif>วิทยาศาสตร์ ศรีราชา</option>
                                                  <option value="วิทยาลัยพาณิชยนาวีนานาชาติ" @if (old('Faculty') == 'วิทยาลัยพาณิชยนาวีนานาชาติ') selected="selected" @endif>วิทยาลัยพาณิชยนาวีนานาชาติ</option>
                                      </select>
                                  </div>
                        </div>
                        <div class="form-group{{ $errors->has('Phone') ? ' has-error' : '' }}">
                          <label for="Phone" class="col-md-4 control-label">Phone<font color ="red">**</font></label>

                          <div class="col-md-6">
                              <input id="Phone" type="text" class="form-control" name="Phone" value="{{ old('Phone') }}" required>

                              @if ($errors->has('Phone'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('Phone') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                    </font>
      							<div class="form-group">
                         <div class="col-md-6 col-md-offset-4">
                           <button type="submit" class="btn btn-primary">
                               <i class="write icon"></i>Submit
                          </button>
                      	</div>              
                    </div>
      						</form>
                  <br>
              </div>
  	       </div>
  	   </div>
       <br>
       <br>
       <br>
  </div>
</div>
@endsection

@section('script')
  $(".navbar-fixed-bottom").fadeToggle();
  $('#rulemodal')
    .modal({
      blurring: true
    })
    .modal('show')
  ;
  $('#hidemodal').on('click', function(){
      $('.ui.modal')
          .modal('hide', function(){
          $('.ui.modal').modal('hide')
      });
  });

@endsection