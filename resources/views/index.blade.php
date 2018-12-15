<!DOCTYPE html>

<html lang="en">
     
    <head>
        <meta charset=utf-8>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Library Reservation</title>
        <!-- Load Roboto font -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <!-- Load css styles -->
        <link rel="stylesheet" href= "{{ asset('dist/css/lightbox.min.css') }}">
        <link rel="stylesheet" type="text/css" href="csss/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="csss/bootstrap-responsive.css" />
        <link rel="stylesheet" type="text/css" href="csss/style.css" />
        <link rel="stylesheet" type="text/css" href="csss/pluton.css" />
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="css/pluton-ie7.css" />
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="csss/jquery.cslider.css" />
        <link rel="stylesheet" type="text/css" href="csss/jquery.bxslider.css" />
        <link rel="stylesheet" type="text/css" href="csss/animate.css" />
        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72.png">
        <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57.png">
        <link rel="stylesheet" type="text/css" href="{{ asset('semantic/semantic.min.css') }}">
        <link rel="shortcut icon" href="images/bs.png">
    </head>
    
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <a href="#" class="brand">
                        <img src="images/logo.png" width="120" height="40" alt="Logo" />
                        <!-- This is website logo -->
                    </a>
                    <!-- Navigation button, visible on small resolution -->
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <i><font color ="#fff" >Menu</font></i>
                    </button>
<!-- Main navigation ***************************************************************************-->
                    <div class="nav-collapse collapse pull-right">
                        <ul class="nav" id="top-navigation">
                            <li class="active"><a href="#index">หน้าหลัก</a></li>
                            <!-- <li><a href="#service">รายละเอียดห้อง</a></li> -->
                            @if (Auth::guest())
                                <li><a href="{{ url('/login') }}">เข้าสู่ระบบ</a></li>     
                            @elseif(Auth::user()->status == 1)
                                <li><a href="{{ url('/userlogin') }}">ดูแลระบบ</a></li>    
                            @elseif(Auth::user()->status == 2)
                                <li><a href="{{ url('/userlogin') }}">ดูแลการจอง</a></li>
                            @elseif(Auth::user()->status == 0)
                                <li><a href="{{ url('/userlogin') }}">จองห้อง</a></li>
                            @endif

                            <li><a href="#service">ประชาสัมพันธ์</a></li>
                            <!-- <li><a href="#price">รายละเอียดห้อง</a></li> -->
                            <li><a href="#contact">ติดต่อห้องสมุด</a></li>
                        </ul>
                    </div>
<!-- End main navigation **************************************************************************-->
                </div>
            </div>
        </div>
        <!-- Start home section -->
        
                    <div class="carousel fade-carousel slide" data-ride="carousel" data-interval="5500" id="bs-carousel">
  <!-- Overlay -->      

                  <div class="overlay"></div>
                  <div id="index">
                  </div>
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                    <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#bs-carousel" data-slide-to="1"></li>
                    <li data-target="#bs-carousel" data-slide-to="2"></li>
                  </ol>
                  
                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">

                    <div class="item slides active">
                      <div class="slide-1"></div>
                      <div class="hero">
                        <div>
                            <h1>YOUR WELCOME</h1>       
                            <br> 
                        </div>
                        <div>
                            <h3>ยินดีต้อนรับสู่ระบบจองห้องศึกษา</h3>
                            <br>
                            <br>
                        </div>
                        <div>
                            <form action=" {{ url('/login') }}">
                                @if (Auth::guest() or Auth::user()->status == 0)
                                    <button class="ui teal button"><i class="write icon"></i>จองห้อง</button>
                                @elseif(Auth::user()->status == 2)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลการจอง</button>
                                @elseif(Auth::user()->status == 1)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลระบบ</button>
                                @endif
                            </form>
                        </div>
                      </div>
                    </div>
                    <div class="item slides">
                      <div class="slide-2"></div>
                      <div class="hero">        
                        <div>
                            <h1>WE ARE EASY</h1>       
                            <br> 
                        </div>
                        <div>
                            <h3>ใช้งานง่าย ทุกที่ ทุกเวลา</h3>
                            <br>
                            <br>
                        </div>
                        <div>
                            <form action=" {{ url('/login') }}">
                                @if (Auth::guest() or Auth::user()->status == 0)
                                    <button class="ui teal button"><i class="write icon"></i>จองห้อง</button>
                                @elseif(Auth::user()->status == 2)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลการจอง</button>
                                @elseif(Auth::user()->status == 1)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลระบบ</button>
                                @endif
                            </form>
                        </div>
                      </div>
                    </div>
                    <div class="item slides">
                      <div class="slide-3"></div>
                      <div class="hero">        
                        <div>
                            <h1>WE ARE SMART</h1>       
                            <br> 
                        </div>
                        <div>
                            <h3>รองรับทุกอุปกรณ์</h3>
                            <br>
                            <br>
                        </div>
                        <div>
                            <form action=" {{ url('/login') }}">
                                @if (Auth::guest() or Auth::user()->status == 0)
                                    <button class="ui teal button"><i class="write icon"></i>จองห้อง</button>
                                @elseif(Auth::user()->status == 2)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลการจอง</button>
                                @elseif(Auth::user()->status == 1)
                                    <button class="ui teal button"><i class="write icon"></i>ดูแลระบบ</button>
                                @endif
                            </form>
                        </div>
                      </div>
                    </div>
                  </div> 

     
        <!-- End home section -->
<!-- ปฏิทิน section start ***************************************************************************-->
        <div class="section primary-section" id="service">
            <div class="container">
                <!-- Start title section -->
                <div class="title">
                    <font id ="textNews">ข่าวประชาสัมพันธ์จากห้องสมุด</font>
                    <br>
                    <br>
                </div>
                <div class="row-fluid">
                    
                </div>
                
            </div>
        </div>
<!-- ปฏิทิน section end ***************************************************************************-->
<!-- จองห้อง section start ***************************************************************************-->
        <div class="section secondary-section" id="portfolio">
            <div class="triangle"></div>
            <div class="container">
                 <div class="ui centered four stackable cards" id = "card">
                    @foreach($New as $New)
                        <div class="card">
                            <div class="panel-heading" id="para2"><img width ="50" height ="50" src="{{ asset('/seimg/pin.png') }}">&nbsp;&nbsp;<b><font color="white">News</font></b>
                              </div>
                              
                            <div class="thumbnail">
                                <img width="700" src="{{ '/thumbnails3/'.$New->ImageName }}">
                                <br>
                                <p> <img width ="22" height ="22" src="{{ asset('/seimg/megaphone.png') }}">&nbsp; <font color ="white">{{ $New->NewTitle }} </font></p>
                                <p> <font color ="white">{{ $New->created_at->format('d M Y') }} </font></p>
                                <div class="mask">
                                    <br>
                                    <p>รายละเอียด</p>
                                    <br>
                                    <p>{{ $New->NewDescription }}</p>
                                    <br>
                                    <a href="{{ url('/showcontent/'.$New->Newid) }}"><button class="ui button" >Read more</button></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
<!-- จองห้อง section end ***************************************************************************-->

<!-- เวลาทำการ****************************************************************************** -->
        
        <div class="section third-section" id ="room">
            <div class="container centered">
                <div class="sub-section">
                    <div class="title clearfix">
                        <div class="pull-left">
                            <h3>Our Rooms</h3>
                        </div>
                        <ul class="client-nav pull-right">
                            <li id="client-prev"></li>
                            <li id="client-next"></li>
                        </ul>
                    </div>
                    
                    <ul class="row client-slider" id="clint-slider">
                        <?php $single = 0 ?>
                        @foreach($Rooms as $Room)
                            @if($Room->RoomPeople == 1 and $single == 0)
                                <li>
                                    <a href="{{ '/thumbnails/'.$Room->ImageName }}" rel="lightbox"><img src="{{ '/thumbnails/'.$Room->ImageName }}" alt="room1" /></a>
                                </li>
                                <?php $single = 1 ?>
                            @endif
                            @if($Room->RoomPeople != 1)
                                <li>
                                    <a href="{{ '/thumbnails/'.$Room->ImageName }}" rel="lightbox"><img src="{{ '/thumbnails/'.$Room->ImageName }}" alt="room1" /></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
<!-- จบเวลาทำการ****************************************************************************** -->

<!-- ติดต่ิเรา section start ***************************************************************************-->
        <div id="contact" class="contact">
            <div class="section secondary-section449">
                <div class="container">
                    <div class="span9 center contact-info">
                        <p><font size="6">ติดต่อห้องสมุด</font></p>
                        <hr class="divider">
                    </div>
                </div>
                    <!-- <div class="map-canvas" id="map-canvas">Loading map...</div> -->
                    <br>
                    <br>
                    <div class="ui centered raised card">
                      <div class="content">
                        <div class="header">
                        <center><i class="announcement icon"></i>วันทำการจองห้อง</div></center>
                        <br>
                        <br>
                        <br>
                        <div>
                            <li>เปิดบริการ วันจันทร์-ศุกร์ เวลา 9.00–19.00 น. และ เสาร์-อาทิตย์ เวลา 9.00-16.30 น.</li>
                            <br>
                            <li>ช่วงสอบจะขยายเวลาเปิดให้บริการเฉพาะห้องศึกษากลุ่ม ชั้น 2 ในวันจันทร์-ศุกร์ เวลา 9.00-22.00 น. และ วันเสาร์-อาทิตย์ เวลา 9.00-18.30 น.</li>
                        </div>
                        <br>
                        <div class="description">
                          <p></p>
                        </div>
                      </div>
                      <div class="extra content">
                        <div class ="pull-right"><i class ="write icon"></i><font color = "black">Library Reservation</font></div>
                      </div>
                    </div>
                
                <div class="container">
                    <div class="span9 center contact-info"> 
                        @if(count($contact) >0)
                            @foreach($contact as $contact)
                                <p class="info-mail">{{ $contact->WebName }}</p>
                                <p>โทรศัพท์ : {{ $contact->Phone }} </p>
                            @endforeach
                        @else
                            <p class="info-mail">-</p>
                            <p>โทรศัพท์ :&nbsp;&nbsp;- </p>
                        @endif
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <!-- Contact section edn -->
        <!-- Footer section start -->
        
        <!-- Footer section end -->
        <!-- ScrollUp button start -->
        <div class="scrollup">
            <a href="#">
                <i class="icon-up-open"></i>
            </a>
        </div>
        <script src="{{ asset('dist/js/lightbox-plus-jquery.min.js') }}"></script>
        <!-- ScrollUp button end -->
        <!-- Include javascript -->
        <script src="js/jquery.js"></script>
        <script type="text/javascript" src="jss/jquery.mixitup.js"></script>
        <script type="text/javascript" src="jss/bootstrap.js"></script>
        <script type="text/javascript" src="jss/modernizr.custom.js"></script>
        <script type="text/javascript" src="jss/jquery.bxslider.js"></script>
        <script type="text/javascript" src="jss/jquery.cslider.js"></script>
        <script type="text/javascript" src="jss/jquery.placeholder.js"></script>
        <script type="text/javascript" src="jss/jquery.inview.js"></script>
        <!-- Load google maps api and call initializeMap function defined in app.js -->
        <!-- css3-mediaqueries.js for IE8 or older
        <[if lt IE 9]>
            <script src="js/respond.min.js"></script>
        <[endif]> -->
        <script type="text/javascript" src="jss/app.js"></script>
        <script src="{{ asset('semantic/semantic.min.js') }}"></script>
        <script src= "{{ asset('jquery-3.2.1.min.js') }}" </script>
    </body>
</html>