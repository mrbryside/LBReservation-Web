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
	        <h2 class="ui left floated header"><font size = "5" color ="#828282">MAIN</font><br> <font size = "6" color ="#B92000">MENU</font></h2>
	          <a href ="{{ url('/home') }}">
	            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
	          </a>
	        <div class="ui clearing divider"></div>
          <div id ="message">
	          @if(count($Closes) > 0)    
		          @if(Session::has('flash_message2'))
		              <div class="alert alert-success"><em><center><li>{!! session('flash_message2') !!}</li></center></em></div></center>
		          @endif
		        @endif
	          @if(Session::has('flash_message'))
	              <div class="alert alert-danger"><em><center><li>{!! session('flash_message') !!}</li></center></em></div></center>
	          @endif
	          @if(count($errors)>0)
	              @foreach($errors->all() as $error)
	                <div class="alert alert-danger"><em><center><li>{{$error}}</li></center></em></div>
	              @endforeach
	          @endif
          </div>
	        <div class="ui grey two item stackable menu" id ="menu">

		        <a class="item" href="#/" id = "closemenu">
		          <img src="{{ asset('/seimg/keysdsd.png') }}"><font size="2">&nbsp;&nbsp;กำหนดเวลาปิดจองห้อง</font></a>
		        </a>
		        @if(Count($Test) == 0)
			        <a class ="item" id ="open">
			          <img src="{{ asset('/seimg/off.png') }}" style="width:35px"><font size="2">&nbsp;ระบบเวลาสอบ</font>
			        </a>  
			    @else
			        <a class ="item" id ="close">
			          <img src="{{ asset('/seimg/on.png') }}" style="width:35px"><font size="2">&nbsp;ระบบเวลาสอบ</font>
			        </a>  
			    @endif
            </div>
            <br>
	        <br>  
	        <div id = "hideform">
		        <form action="{{ url('/scheduleroom/delete/testtime') }}" enctype="multipart/form-data" method="post" onsubmit="return confirm('ต้องการปรับเวลาเป็นช่วง ไม่มีการสอบ ใช่ไหม ?')">
	        		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	        		<input type="hidden" name="_method" value="DELETE">
			        <button type ="submit" id ="formtestclose">
			          <font size="2">&nbsp;Close Test Time</font>
			        </button>  
		        </form> 
		         <form action="{{ url('/scheduleroom/create/testtime') }}" enctype="multipart/form-data" method="post" onsubmit="return confirm('ต้องการปรับเวลาเป็นช่วง สอบ ใช่ไหม ?')">
		    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			        <button type ="submit" id ="formtestopen">
			          <font size="2">&nbsp;Open Test Time</font>
			        </button>  
		        </form> 
		    </div>
          <h2 class="ui left floated header"><font size = "5" color ="#828282">ROOM</font><br><font size = "6" color ="#B92000">SCHEDULE</font></h2>
          <div class="ui clearing divider"></div>    
            @if(count($Closes) > 0)
              <div class="table-responsive table-inverse" id ="table">
                <table class="table table-bordered" id="border">
                  <tr>
                  <thead>
                    <th class="bg-primary">Close Start</th>
                    <th class="bg-primary">Close End</th>
                    <th class="bg-primary">Manage</th>
                  </tr>
                  </thead>                 
                  @foreach($Closes as $Close)
                     <tbody>
                        <tr>
                             <td id ="tablecolor"><font size ="3">วันที่ {{ $Close->CloseStart->format('d/m/Y') }} เวลา {{ $Close->CloseStart->format('H : i') }}</font></td>
                             <td id ="tablecolor"><font size ="3">วันที่ {{ $Close->CloseEnd->format('d/m/Y') }} เวลา {{ $Close->CloseEnd->format('H : i') }}</font></font></td>
                             <td id ="tablecolor">
                             	<form action="{{ url('/scheduleroom/'.$Close->CloseID) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="_method" value="DELETE">
                                      <center><input type="image" src="{{ asset('/seimg/delete.ico') }}" width ="35" height ="30"></center>
                                </form>	
                             </td>
                        </tr>
                      </tbody>
                  @endforeach
               </table>
              </div>
              <span class ="pull-right">{{ $Closes->links() }} </span>
            @else
              <table class="table table-bordered">
                <tr>
                <thead>
                     <td class="bg-danger" id="border"><center><font color ="#383838" size = "3"><b>...ไม่มีการปิดจองห้อง...</b></font></center></th>
                </tr>
                </thead>      
              </table>
            @endif  

          </div>
        </div>
      </div>
      <div class="container" id = "closeform">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                        <br>
                        <br>
                        <h2 class="ui left floated header">
                        <font size = "6" color ="#B92000">FORM</font><br> <font size = "5" color ="#828282">ROOM CLOSE</font>
                        </h2>
                    <div class="ui clearing divider"></div>
                      <div class="ui raised segment">
                        <br>
                        <br>
                        <font size ="3">
                        </font>
                        <form class="form-horizontal" action="{{ url('/scheduleroom') }}" enctype="multipart/form-data" method="post" onsubmit="return confirm('คุณแน่ใจแล้วใช่ไหม ?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <font size ="3">
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Date Start<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="dateStart" class="form-control" id='datetimepicker1' placeholder = "วันที่เริ่มปิด" required>
                                      </div>
                                  </div>

                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Time Start<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="timestart" class="form-control" id='datetimepicker2' placeholder = "เวลาที่เริ่มปิด" required>
                                  </div>

                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Date End<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="dateEnd" class="form-control" id='datetimepicker4' placeholder = "วันที่สิ้นสุดการปิด" required>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-5 control-label">Time End<font color ="red">**</font></label>

                                      <div class="col-md-3">
                                            <input type='text' name ="timeend" class="form-control" id='datetimepicker3' placeholder = "เวลาที่สิ้นสุดการปิด" required>
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

@section('script')
  $('#closemenu').on('click', function(){
    $('#closeform').transition('scale toggle')
    $('#message').fadeOut();
  });

   $('#close').on('click', function(){
   		document.getElementById('formtestclose').click()
   });
   $('#open').on('click', function(){
   		document.getElementById('formtestopen').click()
   });
@endsection

@section('loadtable')
	$(function () {
             $('#datetimepicker1').datetimepicker({
                format: 'DD-MM-YYYY'
            });
        });
        $(function () {
             $('#datetimepicker4').datetimepicker({
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