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
<div class="ui modal" id ="rulemodal">
  @if($rule != 'no')
    <div class="header"><font size ="3" color ="#711400"><i class="announcement icon"></i>{{ $rule->RuleTitle }}</font></div>
  @else
    <div class="header"><font size ="3" color ="#711400"><i class="announcement icon"></i>ไม่มีข้อมูล</font></div>
  @endif
  <div class="scrolling content">
    <font class="rule">
      <p> 
        <b>
          @if($ruleItems != 'no')
            <?php $i=1 ?>
            @foreach($ruleItems as $ruleItem)
              <span>{{$i}}) {{ $ruleItem->ruleText }}</span>
              <br>
              <?php $i++; ?>
            @endforeach
          @else
            <span>ไม่มีข้อมูล</span>
            <br>
          @endif
        <br>
        <br>
        <span class ="pull-right" ><font color ="#711400">LIBRARY RESERVATION </font></span>
        </b>
      </p>
    </font>
    <br>
    <center>
    <div class="ui clearing divider"></div>
    <a id ="hidemodal" class = "btn btn-primary"><font size ="2"><i class="reply icon"></i>Close</font></a>
    </center>
    <br>
  </div>
</div>

<div class="ui modal" id ="howtomodal">
  @if($howto != 'no')
    <div class="header"><font size ="3" color ="#711400"><i class="announcement icon"></i>{{ $howto->RuleTitle }}</font></div>
  @else
    <div class="header"><font size ="3" color ="#711400"><i class="announcement icon"></i>ไม่มีข้อมูล</font></div>
  @endif
  <div class="scrolling content">
    <font class="rule">
      <p> 
        <b>
          @if($howtoItems != 'no')
            <?php $i = 1; ?>
            @foreach($howtoItems as $howtoItem)
              <span>{{$i}}) {{ $howtoItem->ruleText }}</span>
              <br>
              <?php $i++; ?>
            @endforeach
          @else
            <span>ไม่มีข้อมูล</span>
            <br>
          @endif
        <br>
        <span class ="pull-right" ><font color ="#711400">LIBRARY RESERVATION </font></span>
        </b>
      </p>
    </font>
    <br>
    <center>
    <div class="ui clearing divider"></div>
    <a id ="hidemodal2" class = "btn btn-primary"><font size ="2"><i class="reply icon"></i>Close</font></a>
    </center>
    <br>
  </div>
</div>

<div class="container" id ="allmenu">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <br>
            <br>
            <br>
            <br>
            <h2 class="ui left floated header"><font id="menutext-main" size = "5" color ="#828282">MAIN</font><br> <font id="menutext-menu" size = "6" color ="#B92000">MENU</font></h2>
            @if(auth()->user()->status != 0)
              <a href ="{{ url('/adminindex') }}">
                <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
              </a>
            @else
              <a href ="#" id ="howto">
                <h2 class="ui right floated header"><br><font id="howtousetext" size = "3" color ="#5B5B5B">HOW TO USE<i class="arrow right icon"></i></font></h2>
              </a>
            @endif
            <div class="ui clearing divider"></div>
            @if($admin)
                @if(auth()->user()->status == 1)
                  <div class="ui grey four item stackable menu" id ="menu">

                    <a class="item" href="{{ url('/home/create') }}">
                      <img src="{{ asset('/seimg/setting.png') }}" style ="height:24px; width:22px"><font size="2">&nbsp;Create Room</font></a>
                    </a>
                    <a class="item" href="{{ url('/scheduleroom') }}">
                      <img src="{{ asset('/seimg/schedule.png') }}" style ="height:23px; width:23px"><font size="2">&nbsp;&nbsp;Room Schedule</font>
                    </a>
                    <a class="item" href="{{ url('/history') }}">
                      <img src="{{ asset('/seimg/history.png') }}" style ="height:23px; width:24px"><font size="2">&nbsp;History</font>
                    </a>  
                    <a class="item" href="/myreservation">
                      <img src="{{ asset('/seimg/clk.png') }}" style ="height:21px; width:22px">&nbsp;&nbsp;<font size="2">My Reservation</font>
                    </a>                  
                  </div>
                @else
                    <div class="ui grey three item stackable menu" id ="para3">
                      <a class="item" href="{{ url('/reservationsearch') }}">
                        <img src="{{ asset('/seimg/search.png') }}" style ="height:22px; width:22px"><font size="2">&nbsp;&nbsp;Active Search</font>
                      </a>    
                      <a class="item" href="{{ url('/history') }}">
                        <img src="{{ asset('/seimg/history.png') }}" style ="height:23px; width:24px"><font size="2">&nbsp;History</font>
                      </a>                                                                         
                      <a class="item" href="/myreservation">
                          <img src="{{ asset('/seimg/clk.png') }}" style ="height:21px; width:22px">&nbsp;&nbsp;<font size="2">My Reservation</font>
                      </a>  
                                 
                    </div>
                @endif
            @else
              <div class="ui grey two item stackable menu" id ="para3">
                <a class="item" id ="rule">
                    <img src="{{ asset('/seimg/rule.png') }}" style ="height:23px; width:24px">&nbsp;&nbsp;<font size="2">Reservation Rule</font>
                </a>  
                <a class="item" href="/myreservation">
                    <img src="{{ asset('/seimg/clk.png') }}" style ="height:22px; width:24px">&nbsp;&nbsp;<font size="2">My Reservation</font>
                </a>            
              </div>
            @endif
            @if($admin == 0 and $examcheck == 1)
              <div class="ui styled fluid accordion" id ="border" style="margin-top:1.25em;">
                    <div class="title">
                      <i class="dropdown icon"></i>
                       <font color ="#AC2002" id="textexam">ขณะนี้ระบบเปิดให้บริการในช่วงเวลาสอบ</font>
                    </div>
                    <div class="content">
                      <font size ="3">
                        <ul>
                          <span id ="textrule">
                            <li>ระบบช่วงเวลาสอบ : วันจันทร์-ศุกร์ เปิดให้บริการเวลา 9.00-21.30 น. และ วันเสาร์-อาทิตย์ เปิดให้บริการเวลา 9.00-18.30 น.</li> 
                            <li>ระบบให้บริการในช่วงเวลาสอบ เฉพาะห้องศึกษากลุ่ม ชั้น 2 จะเปิดให้บริการในช่วงเวลาสอบทั้ง จันทร์ถึงศุกร์ และ เสาร์อาทิตย์</li>     
                            <li>ห้องมินิเธียร์เตอร์ และห้องศึกษาเดี่ยว รวมถึงห้องศึกษากลุ่มที่ไม่ใช่ ชั้น 2 จะเปิดให้บริการช่วงเวลาสอบ เฉพาะ วันเสาร์อาทิตย์เท่านั้น</li>                                
                          </span>
                        </ul>
                      </font>
                    </div>
                </div>
              <div>
            @endif
          <div id="field2" data-field-id="{{$singleuser}}" ></div>
            <div id = "sharedroom">
              @if($singleuser == 0)
                  @include('loadhtml.sharedroom')
              @endif
            </div>
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
    .btn.btn-success{
        background-color:#F74443 !important;
        border-color:#F74443 !important;
        transition: 0.7s;
    }
    .btn.btn-success:hover{
        background-color:#F00B0B !important;
        border-color:#F00B0B !important;
    }
    .rule{
      font-size:15px !important;
    }
    @media(max-width:767px){
        #menutext-main{
          font-size:18px !important;
        }
        #menutext-menu{
          font-size:25px !important;
        }
        #howtousetext{
          font-size:14px !important;
        }
        #sharedtext{
          font-size:18px !important;
        }
        #toggletext{
          font-size:14px !important;
        }
        #roomtext{
          font-size:25px !important;
        }
        #singletext{
          font-size:18px !important;
        }
        #formtext{
          font-size:25px !important;
        }
        #reservetext{
          font-size:18px !important;
        }

        .rule{
          font-size:13px !important;
        }
    }
    #myBtn {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 30px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        background-color: #711400; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 15px; /* Some padding */
        border-radius: 10px; /* Rounded corners */
    }

    #myBtn:hover {
        background-color: #711400; /* Add a dark-grey background on hover */
    }

@endsection


@section('script')
  $(".navbar-fixed-bottom").fadeToggle();
  $('.ui.accordion')
    .accordion()
  ;
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        if (document.body.scrollTop > 740 || document.documentElement.scrollTop > 740) {
            $('#myBtn').fadeIn();
        } else {
            $('#myBtn').hide();
        }
      }
  }

  function topFunction() {
      document.body.scrollTop = 0; // For Chrome, Safari and Opera 
      document.documentElement.scrollTop = 0; // For IE and Firefox
  }



  $('#rule').on('click', function(){
      $('#rulemodal')
        .modal({
          blurring: true
        })
        .modal('show').modal('refresh')
      ;
  });
  $('#hidemodal').on('click', function(){
      $('.ui.modal')
          .modal('hide', function(){
          $('.ui.modal').modal('hide')
      });
  });
  $('#hidemodal2').on('click', function(){
      $('.ui.modal')
          .modal('hide', function(){
          $('.ui.modal').modal('hide')
      });
  });
  $('#howto').on('click', function(){
      $('#howtomodal')
        .modal({
          blurring: true
        })
        .modal('show').modal('refresh')
      ;
  });
  var singleuser = $('#field2').data("field-id");
  if(singleuser){
    $('#sharedroom').load("{{asset('singleroomuser')}}", function() {
          $('#sharedroom')
              .transition({
                animation  : 'scale in',
                duration   : '0.4s',
              })
          ;
    });
  }
@endsection
