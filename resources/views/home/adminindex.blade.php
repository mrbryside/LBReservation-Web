@extends('layouts.app')

@section('title')
@if(auth()->user()->status == 1)
  <title>Admin Panel</title>
@else
  <title>Staff Panel</title>
@endif
@endsection

@section('stylesheet')
  <style type="text/css">
        p { margin-left:2.0em; margin-right:2.0em; /* Or another measurement unit, like px */ }
            #myBtn {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            bottom: 20px; /* Place the button at the bottom of the page */
            right: 30px; /* Place the button 30px from the right */
            z-index: 99; /* Make sure it does not overlap */
            border: none; /* Remove borders */
            outline: none; /* Remove outline */
            background-color: #555; /* Set a background color */
            color: white; /* Text color */
            cursor: pointer; /* Add a mouse pointer on hover */
            padding: 15px; /* Some padding */
            border-radius: 10px; /* Rounded corners */
            }

        #myBtn:hover {
            background-color: #555; /* Add a dark-grey background on hover */
        }
    </style>

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
                    @if(auth()->user()->status == 1)
                      <a class="navbar-brand" href = "{{ url('/adminindex') }}">
                          <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/admins.png') }}">&nbsp;&nbsp;ADMIN PANEL</b></font>
                      </a>
                    @elseif(auth()->user()->status == 2)
                      <a class="navbar-brand" href = "{{ url('/adminindex') }}">
                          <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/admins.png') }}">&nbsp;&nbsp;STAFF PANEL</b></font>
                      </a>
                    @endif
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
                            @if($adminInfo->status)
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
          @if(auth()->user()->status == 1)
              <h2 class="ui left floated header"><font size = "6" color ="#B92000">MENU</font><br> <font size = "5" color ="#828282">FOR-ADMIN</font></h2>
              <div class="ui clearing divider"></div>
              <div class="ui grey four item stackable menu" id ="para3">
                  <a class="item" href="{{ url('/home') }}">
                    <img src="{{ asset('/seimg/books.png') }}" style ="height:23px; width:23px"><font size="2">&nbsp;&nbsp;Rooms Panel</font></a>
                  </a>
                  <a class="item" href="{{ url('/new') }}">
                    <img src="{{ asset('/seimg/home.ico') }}" style ="height:23px; width:23px"><font size="2">&nbsp;&nbsp;Index Panel</font>
                  </a>
                  <a class="item" href="{{ url('/user/show/staff') }}">
                    <img src="{{ asset('/seimg/user.png') }}" style ="height:23px; width:24px"><font size="2">&nbsp;Staff Panel</font>
                  </a>
                  <a class="item" href="{{ url('/user') }}">
                    <img src="{{ asset('/seimg/admin.png') }}" style ="height:23px; width:22px"><font size="2">&nbsp;&nbsp;User Panel</font>
                  </a>
              </div>
          @else
                <h2 class="ui left floated header"><font size = "6" color ="#B92000">MENU</font><br> <font size = "5" color ="#828282">FOR-STAFF</font></h2>
                <div class="ui clearing divider"></div>
                <div class="ui grey two item stackable menu" id ="para3">
                  <a class="item" href="{{ url('/home') }}">
                    <img src="{{ asset('/seimg/books.png') }}" style ="height:24px; width:23px"><font size="2">&nbsp;Rooms Panel</font></a>
                  </a>
                  <a class="item" href="{{ url('/user') }}">
                    <img src="{{ asset('/seimg/admin.png') }}" style ="height:23px; width:22px"><font size="2">&nbsp;&nbsp;User Panel</font>
                  </a>
              </div>
          @endif                 
          <br>
          <br>
          <h2 class="ui left floated header">

          <font size = "6" color ="#B92000">WORK</font><br> <font size = "4" color ="#828282">TODAY </font><font size = "4" color ="#828282">({{$today->format('d/m/y')}})</font>
          </h2>
          <br>
            <a href="{{ url('/reservationsearchonadmin/') }}" class ="pull-right">
                <span><button class="ui teal button" ><i class="search icon"></i><font size="2">Active Search&nbsp;</font></button></span></a>
            </a>
          
          <div class="ui clearing divider"></div>
          </font>     
          @if (session('status'))
              <div class="alert alert-info">
                  <font size ="3">{{ session('status') }}</font>
              </div>
          @endif
          <div class="table-responsive table-inverse" id="table">
            @include('loadhtml.loadtable')
          </div>        
          <br>
          <br>
        </div>
    </div>
@endsection

@section('css')
  .ui.teal.button{
        background-color:#F74443 !important;
        border-color:#F74443 !important;
        transition: 0.7s;
  }
  .ui.teal.button:hover{
      background-color:#F00B0B !important;
      border-color:#F00B0B !important;
  }
@endsection

@section('loadtable')
    function loadtb() {
      $('#table').load("{{asset('loadtable')}}");
    }
    loadtb();
    setInterval(loadtb, 6000);
@endsection

@section('loadtableinsert')
    $('#table').load("{{asset('loadtable')}}");
    $('#table').transition('vertical flip out');
    $('#table').transition('vertical flip in');
@endsection


