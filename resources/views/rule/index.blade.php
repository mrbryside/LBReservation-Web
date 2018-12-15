@extends('layouts.app')

@section('title')
<title>News Panel</title>
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
                    <a class="navbar-brand" href = "{{ url('/new') }}">
                        <font color ="white" size="3"><b><img width ="40" height ="27" src="{{ asset('/seimg/megaphone.png') }}">&nbsp;&nbsp;INDEX PANEL</b></font>
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
          <a href ="{{ url('/adminindex') }}">
            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
          </a>
          <div class="ui clearing divider"></div>
          @if($admin)
              <div class="ui grey three item stackable menu">
                <a class="item" href="{{ url('/new') }}"><img src="{{ asset('/seimg/icon-news.png') }}"><font size="2">&nbsp;News Manage</font></a>
                <a class="item" href="{{ url('/contact') }}"><img width="12" height="20" src="{{ asset('/seimg/11111.png') }}"><font size="2">&nbsp;&nbsp;Contact Manage</font></a>
                <a class="item" href="{{ url('/rule') }}"><img width ="6" height="24" src="{{ asset('/seimg/2222.png') }}"><font size="2">&nbsp;&nbsp;Rule Manage</font></a>
              </div>
          @endif
          <br>
          <br>
          <h2 class="ui left floated header"><font size = "5" color ="#828282">LIBRARY</font><br> <font size = "6" color ="#B92000">RULE</font></h2>
          <a href ="{{ url('/rule/create') }}">
            <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B"><b>CREATE</b><i class="arrow right icon"></i></font></h2>
          </a>
          <div class="ui clearing divider"></div>
          		  @if(Session::has('flash_message'))
                    <div class="alert alert-success"><em> <center><li>{!! session('flash_message') !!}</li> </center></em></div>
                @endif

                @if(count($rules) > 0)
                  @if(Session::has('flash_message2'))
                      <div class="alert alert-danger"><em> <center><li>{!! session('flash_message2') !!}</li> </center></em></div>
                  @endif
                @endif
                @if(count($rules) > 0)
                    <div class="table-responsive table-inverse" id = "table">
                      <table class="table table-bordered" id ="border">
                        <tr>
                        <thead>
                          <th class="bg-primary">Rule Type</th>
                          <th class="bg-primary">All Text</th>
                          <th class="bg-primary">Manage</th>
                        </tr>
                        </thead>                 
                        
                        @foreach($rules as $rule)
                           <tbody>
                              <tr>
                                   <td id ="tablecolor">
                                     <font size ="3">
                                        @if($rule->RuleType == 'howto') 
                                          วิธีใช้ 

                                        @else
                                          ข้อปฏิบัติ
                                        @endif


                                     </font>
                                   </td>
                                   <td id ="tablecolor">
                                     <font size ="3">
                                        <?php $i=1; ?>
                                        @foreach($ruleItems as $ruleItem)
                                          @if($ruleItem->rule_id == $rule->rule_id)
                                            <span>{{$i}}) {{ $ruleItem->ruleText }}</span>
                                            <br>
                                            <?php $i++; ?>
                                          @endif
                                          
                                        @endforeach
                                     </font>
                                   </td>
                                   <td id ="tablecolor">
                                    <center>
                                      <a href="{{ url('/rule/'.$rule->rule_id.'/edit') }}">
      					                      <span><button style="width:85px;"class="ui teal button" type="submit"><font size="2">Edit</font></button></span></a>
                                       <form style="display:inline;" action="{{ url('/rule/'.$rule->rule_id) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button style="width:85px;"class="ui red button"><i class="eraser icon"></i><font size ="2">Delete</font></button>
                                      </form>		
                                    </center>			               
                                   </td>
                              </tr>
                            </tbody>
                        @endforeach
                     </table>        
                    </div>	                    
                 @else
                 		<div class="table-responsive table-inverse" id="table">
                    <table class="table table-bordered" id ="border">
                      <tr>
                      <thead>
                        <th class="bg-danger"><center><font color ="#383838" size = "3">....ไม่มีข้อมูล....</font></center></th>
                      </tr>
                      </thead>  
                    </table>
                  </div>
                 @endif
                 <br>
                 <br>
          </div>
        </div>
@endsection
