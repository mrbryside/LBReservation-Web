@extends('layouts.app')

@section('title')
<title>Library Reservation</title>
@endsection

@section('navbar')
<nav class="navbar navbar-default navbar-static-top">
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
                            <li><a href="{{ url('/') }}"><i class="home icon"></i><b>Home</b></a></li>
                        @else
                            <li><a href="{{ url('/') }}"><i class="home icon"></i><b>Home</b></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->Firstname }} &nbsp; {{ Auth::user()->Lastname }} &nbsp;<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
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
        <div class="col-md-8 col-md-offset-2" id="contentbox">
            <h2 class="ui left floated header"><font id="logintext" size = "6" color ="#B92000">LOGIN</font><br> <font size = "5" color ="#828282" id ="usertext">STUDENT</font><font size = "5" color ="#828282" id ="admintext">ADMIN</font></h2>
              <a href="#/" id = "showadmin">
                <h2 class="ui right floated header"><br><font id ="admin" size = "4" color ="#5B5B5B" >ADMIN<i class="arrow right icon"></i></font></h2>
              </a>
              <a href="#/" id = "showuser">
                <h2 class="ui right floated header"><br><font id ="user" size = "4" color ="#5B5B5B"  >STUDENT<i class="arrow right icon"></i></font></h2>
              </a>
            <div class="ui clearing divider"></div>
            <div class="ui raised segment">
                <br>
                <br>
                @if(Session::has('flash_message'))
                    <div class="alert alert-danger" id = "loginerror"><em> <font size ="3"><center><li>{!! session('flash_message') !!}</li></center></font></em></div>
                @endif
                @if(Session::has('flash_message3'))
                    <div class="alert alert-danger" id = "loginerror2"><em> <font size ="3"><center><li>{!! session('flash_message3') !!}</li></center></font></em></div>
                @endif
                @if(Session::has('flash_message2'))
                    <div class="alert alert-info" id = "logoutsuccess"><em> <font size ="3"><center><li>{!! session('flash_message2') !!}</li></center></font></em></div>
                @endif
                <form class="form-horizontal" method="POST" action="{{ route('login') }}" id ="loginadmin" onsubmit="return confirm('Are you Sure?')">
                    {{ csrf_field() }}
                    <font size = "3">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">User ID</label>

                            <div class="col-md-6">
                                
                                <input id="email" type="text" class="form-control" name="StudentID" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="sign in icon"></i>Login
                                </button>
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    <b>Forgot Your Password?</b>
                                </a>
                            </div>
                        </div>
                    </font>
                </form>
                <div id ="loginuser">
                    <center>
                        <center>
                        <img style="" class="img-responsive" id="person" src="{{ '/seimg/loginperson.jpg' }}"></center>
                        <br>
                        <h4 class="ui horizontal divider header">
                            <font size = "5" color = "#5B5B5B"><b><img src="{{ asset('/seimg/1111.png') }}" width = "30" height = "27">&nbsp;ONLY @ KU.TH</b></font>
                        </h4>                
                        <br>
                        <center><button class="huge ui google plus button" id ="logingoogle" style="border-radius:40px;">
                          <i class="google plus icon"></i>
                          Login With Google KU
                        </button></center>
                        <br>
                        <center><button class="huge ui linkedin button form" id ="logoutgoogle" style ="border-radius:40px;width:206px;">
                          <i class="google plus icon pull-left"></i>
                          Logout Google
                        </button></center>
                    </center>
                    <br>
                </div>
                <br>
            </div>
        </div>
        <br>
        <br>
        <br>
        <input id = "loginfail" type ="hidden" @if(Session::has('flash_message'))  value = "1" @else  value = "0"@endif>
    </div>
</div>
@endsection


@section('script')
  if(document.getElementById('loginfail').value == 0){
    $('#loginadmin').hide();
    $('#showuser').hide();
    $('#user').hide();
    $('#admintext').hide();
  }
  else{
    $('#loginuser').hide();
    $('#showadmin').hide();
    $('#admin').hide();
    $('#usertext').hide();
  }
  
  $('#showadmin').on('click', function(){
     $('#loginuser').hide();
     $('#loginadmin').fadeIn("slow");
     $('#showuser').fadeIn("slow");
     $('#admin').hide();
     $('#user').fadeIn("slow");
     $('#admintext').fadeIn("slow");
     $('#usertext').hide();
     $('#showadmin').hide();
     $('#loginerror2').hide();
     $('#logoutsuccess').hide();
  });

  $('#showuser').on('click', function(){
     $('#loginadmin').hide();
     $('#loginuser').fadeIn("slow");
     $('#showadmin').fadeIn("slow");
     $('#user').hide();
     $('#admin').fadeIn("slow");
     $('#admintext').hide();
     $('#usertext').fadeIn("slow");
     $('#showuser').hide();
     $('#loginerror').hide();
  });

  $('#logingoogle').on('click', function(){
        var APP_URL = {!! json_encode(url('/login/google')) !!}
        javascript:location.href=APP_URL;
  });

  $('#logoutgoogle').on('click', function(){
        var APP_URL = {!! json_encode(url('/logoutgoogle')) !!}
        javascript:location.href='https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='+APP_URL;
  });

@endsection

@section('css')
    #person{
        width:180px;
        height:180px;
    }
    #contentbox{
      padding-top:15px;
    }
    @media(max-width:767px){
        #person{
            width:120px;
            height:120px;
        }
        #contentbox{
          padding-top:2px;
        }
        #logintext{
          font-size:25px !important;
        }
        #usertext{
          font-size:18px !important;
        }
        #admintext{
          font-size:18px !important;
        }
        #user{
          font-size:14px !important;
        }
        #admin{
          font-size:14px !important;
        }
    }
@endsection