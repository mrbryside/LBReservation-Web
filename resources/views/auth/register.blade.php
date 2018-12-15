@extends('layouts.app')

@section('title')
<title>Library Reservation</title>
@endsection

@section('navbar')
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
                            <li><a href="{{ url('/') }}"><i class="home icon"></i><b>Home</b></a></li>
                            <li><a href="{{ url('/adminindex') }}"><i class="sign in icon"></i><b>Login</b></a></li>
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
<div class="container" id = "allmenu">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <br>
            <br>
            <br>
            <br>
            <h2 class="ui left floated header"><font size = "6" color ="#B92000">REGISTER</font><br> <font size = "5" color ="#828282">FOR-RESERVATION</font></h2>
              <a href="{{ route('login') }}">
                <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="arrow right icon"></i></font></h2>
              </a>
            <div class="ui clearing divider"></div>
            <div class="ui raised segment">
                <br>
                <br>
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <font size ="3">
                          @if(Session::has('flash_message'))
                              <div class="alert alert-danger"><em><center><li>{!! session('flash_message') !!}</li></center></em></div>
                          @endif
                        <div class="form-group{{ $errors->has('StudentID') ? ' has-error' : '' }}">
                            <label for="StudentID" class="col-md-4 control-label">User ID (Student ID)</label>

                            <div class="col-md-6">
                                <input id="StudentID" type="StudentID" class="form-control" name="StudentID" value="{{ old('StudentID') }}" required>

                                @if ($errors->has('StudentID'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('StudentID') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('Firstname') ? ' has-error' : '' }}">
                            <label for="Firstname" class="col-md-4 control-label">Firstname</label>

                            <div class="col-md-6">
                                <input id="Firstname" type="text" class="form-control" name="Firstname" value="{{ old('Firstname') }}" required autofocus>

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
                                <input id="Lastname" type="text" class="form-control" name="Lastname" value="{{ old('Lastname') }}" required autofocus>

                                @if ($errors->has('Lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                                <label for="Faculty" class="col-md-4 control-label">Faculty</label>
                                <div class="col-md-6">
                                    <select  class ="form-control" id="Faculty" class="drop" name="Faculty" required autofocus>
                                                <option value="" selected disabled>Please select</option>
                                                <option value="คณะวิศวกรรมศาสตร์ ศรีราชา" @if (old('Faculty') == 'คณะวิศวกรรมศาสตร์ ศรีราชา') selected="selected" @endif>คณะวิศวกรรมศาสตร์ ศรีราชา</option>
                                                <option value="คณะวิทยาการจัดการ" @if (old('Faculty') == 'คณะวิทยาการจัดการ') selected="selected" @endif>คณะวิทยาการจัดการ</option>
                                                <option value="เศรษฐศาสตร์ ศรีราชา" @if (old('Faculty') == 'เศรษฐศาสตร์ ศรีราชา') selected="selected" @endif>เศรษฐศาสตร์ ศรีราชา</option>
                                                <option value="วิทยาศาสตร์ ศรีราชา" @if (old('Faculty') == 'วิทยาศาสตร์ ศรีราชา') selected="selected" @endif>วิทยาศาสตร์ ศรีราชา</option>
                                                <option value="วิทยาลัยพาณิชยนาวีนานาชาติ" @if (old('Faculty') == 'วิทยาลัยพาณิชยนาวีนานาชาติ') selected="selected" @endif>วิทยาลัยพาณิชยนาวีนานาชาติ</option>
                                    </select>
                                </div>
                        </div>
                        

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('Phone') ? ' has-error' : '' }}">
                            <label for="Phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="Phone" type="text" class="form-control" name="Phone" value="{{ old('Phone') }}" required autofocus>

                                @if ($errors->has('Phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autofocus>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autofocus>
                            </div>
                        </div>


                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="write icon"></i>Register
                            </button>
                        </div>
                    </div>
                    </font>
                </form>
            </div>
            <br>
            <br>
    </div>
</div>
@endsection
