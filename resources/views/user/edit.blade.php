@extends('layouts.app')

@section('title')
<title>Account Management</title>
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
                    <a class="navbar-brand" href = "{{ url('/user/manage/'.auth()->user()->id) }}">
                        <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/11.png') }}">&nbsp;&nbsp;MY ACCOUNT</b></font>
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
<div class="container" id = "allmenu">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <br>
            <br>
            <br>
            <br>
            <h2 class="ui left floated header"><font id="edittext" size = "6" color ="#B92000">EDIT</font><br><font id="profiletext" size = "5" color ="#828282">PROFILE</font></h2>
            @if($staff->id == Auth::user()->id)
                <a href ="{{ url('/user/manage/'.Crypt::encrypt(Auth::user()->id)) }}">
                  <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
                </a>
            @else

                <a href ="{{ url('/user/'.$staff->id) }}">
                  <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
                </a>
            @endif
            <div class="ui clearing divider"></div>
            <div class="ui raised segment">
                <br>
                <br>
                @if($staff->id == Auth::user()->id)
                    <form class="form-horizontal" method="POST" action="{{ url('/user/manage/infomation/'.Crypt::encrypt(Auth::user()->id)) }}" onsubmit="return confirm('คุณแน่ใจที่จะเปลี่ยนข้อมูล Profile ใช่ไหม?')">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <font size ="3">
                            @if(Session::has('flash_message'))
                              <div class="alert alert-danger"><em><center><li>{!! session('flash_message') !!}</li></center></em></div></center>
                            @endif
                            @if(auth()->user()->status == 0)
                                <div class="form-group{{ $errors->has('StudentID') ? ' has-error' : '' }}">
                                    <label for="StudentID" class="col-md-4 control-label">Student ID</label>

                                    <div class="col-md-6">
                                        <input id="StudentID" type="text" class="form-control" name="StudentID" value="{{ Auth::user()->StudentID }}" required>

                                        @if ($errors->has('StudentID'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('StudentID') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <input id="StudentID" type="hidden" name="StudentID" value="{{ Auth::user()->StudentID }}">

                            @endif
                            <div class="form-group{{ $errors->has('Firstname') ? ' has-error' : '' }}">
                                <label for="Firstname" class="col-md-4 control-label">Firstname</label>

                                <div class="col-md-6">
                                    <input id="Firstname" type="text" class="form-control" name="Firstname" value="{{ Auth::user()->Firstname }}" required>

                                    @if ($errors->has('Firstname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('Firstname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('Lastname') ? ' has-error' : '' }}">
                                <label for="Lastname" class="col-md-4 control-label">Lastname</label>

                                <div class="col-md-6">
                                    <input id="Lastname" type="text" class="form-control" name="Lastname" value="{{ Auth::user()->Lastname }}" required>

                                    @if ($errors->has('Lastname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('Lastname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if(Auth::user()->status == 0)
                            <div class="form-group">
                                    <label for="Faculty" class="col-md-4 control-label">Faculty</label>
                                    <div class="col-md-6">
                                        <select  class ="form-control" id="Faculty" class="drop" name="Faculty" required>
                                                    <option value="" selected disabled>Please select</option>
                                                    <option value="คณะวิศวกรรมศาสตร์ ศรีราชา" @if (Auth::user()->Faculty == 'คณะวิศวกรรมศาสตร์ ศรีราชา') selected="selected" @endif>คณะวิศวกรรมศาสตร์ ศรีราชา</option>
                                                    <option value="คณะวิทยาการจัดการ" @if (Auth::user()->Faculty == 'คณะวิทยาการจัดการ') selected="selected" @endif>คณะวิทยาการจัดการ</option>
                                                    <option value="เศรษฐศาสตร์ ศรีราชา" @if (Auth::user()->Faculty == 'เศรษฐศาสตร์ ศรีราชา') selected="selected" @endif>เศรษฐศาสตร์ ศรีราชา</option>
                                                    <option value="วิทยาศาสตร์ ศรีราชา" @if (Auth::user()->Faculty == 'วิทยาศาสตร์ ศรีราชา') selected="selected" @endif>วิทยาศาสตร์ ศรีราชา</option>
                                                    <option value="วิทยาลัยพาณิชยนาวีนานาชาติ" @if (Auth::user()->Faculty == 'วิทยาลัยพาณิชยนาวีนานาชาติ') selected="selected" @endif>วิทยาลัยพาณิชยนาวีนานาชาติ</option>
                                        </select>
                                    </div>
                            </div>
                            @else
                                <div id = "hide">
                                    <select id="Faculty" class="drop" name="Faculty">
                                        <option  value="-" >-</option>
                                    </select>
                                </div>
                            @endif

                            @if(Auth::user()->status != 0)
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @else
                                <input id="email" type="hidden" class="form-control" name="email" value="{{ Auth::user()->email }}">
                            @endif

                            <div class="form-group{{ $errors->has('Phone') ? ' has-error' : '' }}" >
                                <label for="Phone" class="col-md-4 control-label">Phone</label>

                                <div class="col-md-6">
                                    <input id="Phone" type="text" class="form-control" name="Phone" value="{{ Auth::user()->Phone }}" required>

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
                @else
                    <form class="form-horizontal" method="POST" action="{{ url('/user/manage/infomation/'.Crypt::encrypt($staff->id)) }}" onsubmit="return confirm('คุณแน่ใจที่จะเปลี่ยนข้อมูล Profile ใช่ไหม?')">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <font size ="3">
                            @if(Session::has('flash_message'))
                              <div class="alert alert-danger"><em><center><li>{!! session('flash_message') !!}</li></center></em></div></center>
                            @endif
                            @if($staff->status == 0)
                                <div class="form-group{{ $errors->has('StudentID') ? ' has-error' : '' }}">
                                    <label for="StudentID" class="col-md-4 control-label">Student ID</label>

                                    <div class="col-md-6">
                                        <input id="StudentID" type="text" class="form-control" name="StudentID" value="{{ $staff->StudentID }}" required>

                                        @if ($errors->has('StudentID'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('StudentID') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <input id="StudentID" type="hidden" name="StudentID" value="{{ $staff->StudentID }}">

                            @endif
                            <div class="form-group{{ $errors->has('Firstname') ? ' has-error' : '' }}">
                                <label for="Firstname" class="col-md-4 control-label">Firstname</label>

                                <div class="col-md-6">
                                    <input id="Firstname" type="text" class="form-control" name="Firstname" value="{{ $staff->Firstname }}" required>

                                    @if ($errors->has('Firstname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('Firstname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('Lastname') ? ' has-error' : '' }}">
                                <label for="Lastname" class="col-md-4 control-label">Lastname</label>

                                <div class="col-md-6">
                                    <input id="Lastname" type="text" class="form-control" name="Lastname" value="{{ $staff->Lastname }}" required>

                                    @if ($errors->has('Lastname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('Lastname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($staff->status == 0)
                            <div class="form-group">
                                    <label for="Faculty" class="col-md-4 control-label">Faculty</label>
                                    <div class="col-md-6">
                                        <select  class ="form-control" id="Faculty" class="drop" name="Faculty" required>
                                                    <option value="" selected disabled>Please select</option>
                                                    <option value="คณะวิศวกรรมศาสตร์ ศรีราชา" @if ($staff->Faculty == 'คณะวิศวกรรมศาสตร์ ศรีราชา') selected="selected" @endif>คณะวิศวกรรมศาสตร์ ศรีราชา</option>
                                                    <option value="คณะวิทยาการจัดการ" @if ($staff->Faculty == 'คณะวิทยาการจัดการ') selected="selected" @endif>คณะวิทยาการจัดการ</option>
                                                    <option value="เศรษฐศาสตร์ ศรีราชา" @if ($staff->Faculty == 'เศรษฐศาสตร์ ศรีราชา') selected="selected" @endif>เศรษฐศาสตร์ ศรีราชา</option>
                                                    <option value="วิทยาศาสตร์ ศรีราชา" @if ($staff->Faculty == 'วิทยาศาสตร์ ศรีราชา') selected="selected" @endif>วิทยาศาสตร์ ศรีราชา</option>
                                                    <option value="วิทยาลัยพาณิชยนาวีนานาชาติ" @if ($staff->Faculty == 'วิทยาลัยพาณิชยนาวีนานาชาติ') selected="selected" @endif>วิทยาลัยพาณิชยนาวีนานาชาติ</option>
                                        </select>
                                    </div>
                            </div>
                            @else
                                <div id = "hide">
                                    <select id="Faculty" class="drop" name="Faculty">
                                        <option  value="-" >-</option>
                                    </select>
                                </div>
                            @endif

                            @if($staff->status != 0)
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $staff->email }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @else
                                <input id="StudentIDOld" type="hidden" class="form-control" name="StudentIDOld" value="{{ $staff->StudentID }}">
                                <input id="email" type="hidden" class="form-control" name="email" value="{{ $staff->email }}">
                            @endif

                            <div class="form-group{{ $errors->has('Phone') ? ' has-error' : '' }}" >
                                <label for="Phone" class="col-md-4 control-label">Phone</label>

                                <div class="col-md-6">
                                    <input id="Phone" type="text" class="form-control" name="Phone" value="{{ $staff->Phone }}" required>

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
                @endif
                
                <br>
            </div>
            <br>
            <br>
    </div>
</div>
@endsection

@section('css')
  @media(max-width:767px){
        #edittext{
          font-size:20x !important;
        }
        #profiletext{
          font-size:18px !important;
        }
        #backtext{
          font-size:14px !important;
        }
    }
@endsection

@section('script')
    $('#hide').hide();

@endsection