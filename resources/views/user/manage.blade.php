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
                    @if($information == 0)
                      <a class="navbar-brand" href = "{{ url('/user/manage/'.auth()->user()->id) }}">
                          <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/11.png') }}">&nbsp;&nbsp;MY ACCOUNT</b></font>
                      </a>
                    @else
                      @if($Users->status == 2)
                      <a class="navbar-brand" href = "{{ url('/user/show/staff') }}">
                        <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/11.png') }}">&nbsp;&nbsp;STAFF PANEL</b></font>
                      </a>
                      @else
                        <a class="navbar-brand" href = "{{ url('/user/') }}">
                          <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/11.png') }}">&nbsp;&nbsp;USER PANEL</b></font>
                        </a>
                      @endif
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
<div class="container" id="allmenu">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <br>
            <br>
            <br>
            <br>
            @if($information == 0)
              <h2 class="ui left floated header"><font id="maintext" size = "5" color ="#828282">MAIN</font><br> <font id="menutext"size = "6" color ="#B92000">MENU</font></h2>
              <a href ="{{ url('/userlogin') }}">
                <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
              <div class="ui clearing divider"></div>
              @if($information == 0)  
                @if(auth()->user()->status != 0)
                <div class="ui grey two item stackable menu" id ="menu">    
                    <a class="item" href ="{{ url('/user/manage/password/'.Crypt::encrypt(Auth::user()->id)) }}">
                        <img src="{{ asset('/seimg/keyblue.png') }}"><font size="2">&nbsp;Change Password</font></a>
                    </a>
                    <a class="item" href ="{{ url('/user/manage/infomation/'.Crypt::encrypt(Auth::user()->id)) }}">
                        <img src="{{ asset('/seimg/1111.png') }}"><font size="2">&nbsp;Profile Edit</font></a>
                    </a>      
                </div>    
                @else
                  <div class="ui grey one item stackable menu" id ="menu">    
                    <a class="item" href ="{{ url('/user/manage/infomation/'.Crypt::encrypt(Auth::user()->id)) }}">
                        <img src="{{ asset('/seimg/1111.png') }}"><font size="2">&nbsp;Profile Edit</font></a>
                    </a>      
                  </div>    
                @endif     
              @endif
              
            @else
              @if($information != 0 && auth()->user()->status == 1)
                <h2 class="ui left floated header"><font id="maintext" size = "5" color ="#828282">MAIN</font><br> <font id="menutext" size = "6" color ="#B92000">MENU</font></h2>
                @if($Users->status == 2)
                  <a href ="{{ url('/user/show/staff') }}">
                      <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
                  </a>
                @else
                   <a href ="{{ url('/user') }}">
                      <h2 class="ui right floated header"><br><font id="backtext" size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
                  </a>
                @endif
                <div class="ui clearing divider"></div>
                <div @if($Users->status != 0) class="ui grey two item stackable menu" @else class="ui grey one item stackable menu" @endif id ="menu">  
                    @if($Users->status != 0)
                      <a class="item" href ="{{ url('/user/manage/password/'.Crypt::encrypt($Users->id)) }}">
                          <img src="{{ asset('/seimg/keyblue.png') }}"><font size="2">&nbsp;Change Password</font></a>
                      </a>  
                    @endif
                    <a class="item" href ="{{ url('/user/manage/infomation/'.Crypt::encrypt($Users->id)) }}">
                        <img src="{{ asset('/seimg/1111.png') }}"><font size="2">&nbsp;Profile Edit</font></a>
                    </a>      
                  </div>   
              @endif
            @endif
            <br>
            <br>
            @if($information != 0)
            <h2 class="ui left floated header">
              @if($Users->status == 2)
                <font id="statustext" size = "5" color ="#828282">STAFF</font>
              @else
                <font id="statustext" size = "5" color ="#828282">USER</font>
              @endif            
              <br> 
              <font id="profiletext" size = "6" color ="#B92000">PROFILE</font>
            </h2>
            @else
              <h2 id="statustext" class="ui left floated header"><font size = "5" color ="#828282">YOUR</font><br> <font id="profiletext" size = "6" color ="#B92000">PROFILE</font></h2>
            @endif

            @if(Auth::user()->status == 2 && $information != 0)
              <a href ="{{ url('/user/') }}">
                  <h2 id="backtext" class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
            @endif
            <div class="ui clearing divider"></div>
            <div class="ui piled segment">
              <br>
              <br>
              <br>
              <div class="row">
                <br>
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="{{ asset('/seimg/user.png') }}" class="img-circle img-responsive"> </div>
                <br>
                <div class=" col-md-9 col-lg-9 "> 
                  <font size ="3">                
                    @if(Session::has('flash_message'))
                          <div class="alert alert-info"><em><center><li>{!! session('flash_message') !!}</li></center></em></div>
                    @endif
                    <div class="table-responsive table-inverse" id="table">
                    @if($Users->status == 0)
                      <table class="table table-user-information">
                        <tbody>
                          <tr>
                            <td><b>Student ID </b></td>
                            <td>{{ $Users->StudentID }}</td>
                          </tr>
                          <tr>
                            <td><b>Firstname </b></td>
                            <td>{{ $Users->Firstname }}</td>
                          </tr>
                          <tr>
                            <td><b>Lastname </b></td>
                            <td>{{ $Users->Lastname }}</td>
                          </tr>
                          <tr>
                            <td><b>Faculty </b></td>
                            <td>{{ $Users->Faculty }}</td>
                          </tr>
                          <tr>
                            <td><b>Email </b></td>
                            <td>{{ $Users->email }}</td>
                          </tr>   
                          <tr>           
                            <td><b>Phone Number </b></td>
                            <td>{{ $Users->Phone }}
                            </td>
                               
                          </tr>
                          <tr>           
                            <td><b>Count Ban </b></td>
                            <td>{{ $Users->CountBan }}
                            </td>
                               
                          </tr>
                         
                        </tbody>
                      </table>
                    @elseif($Users->status == 2)
                      <table class="table table-user-information">
                      <tbody>
                        <tr>
                          <td><b>Staff ID </b></td>
                          <td>{{ $Users->StudentID }}</td>
                        </tr>
                        <tr>
                          <td><b>Firstname </b></td>
                          <td>{{ $Users->Firstname }}</td>
                        </tr>
                        <tr>
                          <td><b>Lastname </b></td>
                          <td>{{ $Users->Lastname }}</td>
                        </tr>
                        <tr>
                          <td><b>Email </b></td>
                          <td>{{ $Users->email }}</td>
                        </tr>              
                          <td><b>Phone Number </b></td>
                          <td>{{ $Users->Phone }}
                          </td>
                             
                        </tr>
                       
                      </tbody>
                    </table>
                    
                   @elseif($Users->status == 1)
                      <table class="table table-user-information">
                      <tbody>
                        <tr>
                          <td><b>Admin ID </b></td>
                          <td>{{ $Users->StudentID }}</td>
                        </tr>
                        <tr>
                          <td><b>Firstname </b></td>
                          <td>{{ $Users->Firstname }}</td>
                        </tr>
                        <tr>
                          <td><b>Lastname </b></td>
                          <td>{{ $Users->Lastname }}</td>
                        </tr>
                        <tr>
                          <td><b>Email </b></td>
                          <td>{{ $Users->email }}</td>
                        </tr>              
                          <td><b>Phone Number </b></td>
                          <td>{{ $Users->Phone }}
                          </td>
                             
                        </tr>
                       
                      </tbody>
                    </table>               
                  @endif
                  </div>
                </div>            
              </font>
            </div>
              <br>
              <br>
              <br>
              <br>
          </div>   
          <br>
          <br>      
      </div>
    </div>
@endsection

@section('css')
  @media(max-width:767px){
        #maintext{
          font-size:22x !important;
        }
        #menutext{
          font-size:24px !important;
        }
        #backtext{
          font-size:16px !important;
        }
        #statustext{
          font-size:22px !important;
        }
        #profiletext{
          font-size:24px !important;
        }
    }
@endsection


