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
        <div class="col-md-8 col-md-offset-2">
          <br>
          <br>
          <br>
          <br>
          <h2 class="ui left floated header"><font size = "6" color ="#B92000">EDIT</font><br> <font size = "5" color ="#828282">ROOM</font></h2>
          <a href ="{{ url('/home') }}">
            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
          </a>
        <div class="ui clearing divider"></div>
            <div class="ui raised segment">
              <br>
              @if(count($errors)>0)
                <ul>
                  @foreach($errors->all() as $error)
                    <li class ="alert alert-danger"><font size ="3" >{{$error}}</font></li>
                  @endforeach
                </ul>
              @endif
              <br>
    						<form class="form-horizontal" action="{{ url('/home/'.$Room->Roomid) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
    							<input type="hidden" name="_token" value="{{ csrf_token() }}">
    							<input type="hidden" name="_method" value="PUT">
                  <font size ="3">
        							<div class="form-group">
        								<label class="col-md-4 control-label">Room Name<font color ="red">**</font></label>
        								<div class="col-md-6">
        									<input type="text" class="form-control" name="RoomName" value="{{ $Room->RoomName }}" required>
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-md-4 control-label">Room Description<font color ="red">**</font></label>
        								<div class="col-md-6">
        									<textarea name="RoomDescription" class="form-control" required>{{$Room->RoomDescription}}</textarea>
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-md-4 control-label">Room People<font color ="red">**</font></label>
        								<div class="col-md-6">
        									<input type="string" name="RoomPeople" class="form-control" value="{{ $Room->RoomPeople }}" required>
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-md-4 control-label">Room Floor<font color ="red">**</font></label>
        								<div class="col-md-6">
                          <select  class ="form-control pull-left" class="drop" name="RoomFloor">
                              <option selected disabled>Please select</option>
                              @for($i = 1;$i<=4;$i++)
                                <option value="{{ isset($i) ? $i : ''}}" @if ($Room->RoomFloor == $i) selected="selected" @endif >{{$i}}</option>
                              @endfor
                          </select>
                        </div>
        							</div>
        							<div class="form-group">
        								<label class="col-md-4 control-label">Image</label>
        								<div class="col-md-6">
        									<input type="file" name="ImageName">(jpeg,jpg,png)
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
         <br>
         <br>
         <br>
    </div>
</div>
@endsection