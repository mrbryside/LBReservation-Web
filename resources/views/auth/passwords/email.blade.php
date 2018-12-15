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
                            <li><a href="{{ url('/login') }}"><i class="sign in icon"></i><b>Login</b></a></li>
                        @else
                            <li><a href="{{ url('/') }}"><i class="home icon"></i>Home</a></li>
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
        <div class="col-md-8 col-md-offset-2">
                    <br>
                    <h2 class="ui left floated header"><font id="resettext" size = "6" color ="#B92000">RESET</font><br> <font id="passtext" size = "5" color ="#828282">PASSWORD</font></h2>
                      <a href="{{ route('login') }}">
                        <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="arrow right icon"></i></font></h2>
                      </a>
                    <div class="ui clearing divider"></div>
                    <div class="ui raised segment">
                        <br>
                        <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <font size ="3" >อีเมลล์สำหรับ รีเซ็ตพาสเวิร์ด ได้ถูกส่งทางอีเมลล์คุณแล้ว!</font>
                        </div>
                    @endif
                    @if(Session::has('flash_message'))
                        <div class="alert alert-danger"><em><center><font size ="3"><li>{!! session('flash_message') !!}</li></center></font></em></div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <font size = "3">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </font>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="share icon"></i>Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
  @media(max-width:767px){
        #resettext{
          font-size:25x !important;
        }
        #passtext{
          font-size:18px !important;
        }
        #backtext{
          font-size:14px !important;
        }
    }
@endsection
