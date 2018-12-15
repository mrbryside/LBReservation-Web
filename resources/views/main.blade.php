@extends('layouts.mainlayout')


@section('title')
<title>Library Reservation</title>
<link rel="shortcut icon" href="{{ asset('/images/bs.png') }}">
@endsection



@section('content')
	<div style="position: relative;">
		<div class="background" id="section1">
			
		</div>
		<div class="background-another" style="display:inline;position: absolute;top: 0;left: 0;width:100%;" id="particles-js">
			
		</div>
		<div class="container" style="display:inline;position: absolute;top: 0;left: 0;width:100%;height:30px;">
			<nav class="navbar" id="navbarMenu">
				<div class="navbar-header">
				  <a href="#section1">
				  	<font class="header-red"><img src="{{ asset('/image/open-book.png') }}" class="bookLogo">LBReservation</font>
				  </a>
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <img src="{{ asset('/image/line-menu.png') }}" class="burgerMenu">               
			      </button>
			    </div>
			    <div class="navbar-header">
				    <div class="collapse navbar-collapse" id="myNavbar" style="padding:0;margin:0;border:0;">
					    <ul class="nav navbar-nav">
					      <li class="active"><a id="navbarLink" href="#section1">หน้าหลัก</a></li>
					      <li><a id="navbarLink2" href="#section2">ประชาสัมพันธ์</a></li>
					      <li><a id="navbarLink3" href="#section3">ภาพห้องศึกษา</a></li>
					      <li><a id="navbarLink4" href="#section4">ติดต่อห้องสมุด</a></li>
					    </ul>
					 </div>
				</div>
			</nav>	
			<div class="col-md-12 col-sm-12 col-xs-12" id="allTextWelcome">
				<div class="col-md-12" style="text-align: center;">
					<font id="welcomeText">ระบบจองห้องศึกษาออนไลน์</font>
				</div>
				<div class="col-md-12 columDes" style="text-align: center;">
					<font id="desText" >สะดวก อัตโนมัติ มีประสิทธิภาพ ทุกอุปกรณ์</font>
				</div>
				<div class="col-md-12 columButton" style="text-align: center;">
					<form action=" {{ url('/userlogin') }}">
						@if (Auth::guest() or Auth::user()->status == 0)
	                        <button id="button-menu"><font id="textButton">เข้าสู่ระบบ</font></button>
	                    @elseif(Auth::user()->status == 2)
	                        <button id="button-menu"><font id="textButton">ดูแลการจอง</font></button>
	                    @elseif(Auth::user()->status == 1)
	                        <button id="button-menu"><font id="textButton">ดูแลระบบ</font></button>
	                    @endif
	                </form>
				</div>
			</div>
		</div>
		<span style="margin-top:-16px;height:16px;" class="anchor" id="section2"></span>
		<div clas="container">
			<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;margin-top:40px">
				<center><font id="headerSection">ข่าวประชาสัมพันธ์</font></center>
				<center><div class="divider" style="margin-bottom:15px;"></div></center>
				<center><font id="desSection">ประกาศและข่าวสาร จากห้องสมุด</font></center>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;margin-top:40px">
				<a id="moreClick" style="cursor: pointer;"><font id ="moreDown"class="moreNews pull-right"><i class="glyphicon glyphicon-sort-by-attributes"></i>&nbsp;แสดงเพิ่ม</font></a>

				<a id="moreClick2" style="cursor: pointer;"><font id ="moreUp" class="moreNews pull-right"><i class="	glyphicon glyphicon-arrow-up"></i>&nbsp;แสดงลดลง</font></a>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 allcard" id="allcard_id">
				@foreach($New as $New)
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 columcard">
						<div class="card">
						  <div class="col-md-6 col-sm-6 col-xs-6" id="contentcard">
							<img src="{{ asset('/thumbnails3/'.$New->ImageName) }}" style="width:100%;height:100%;">
						  </div>
						  <div class="col-md-6 col-sm-6 col-xs-6" id="contentcard2">
						  		<font id="card-des" style="font-weight: bold;padding-left:1vw;padding-right:0.4vw;">รายละเอียด</font>
						  		<p id="cardParagraph" style="padding-top:14px;padding-left:1vw;padding-right:0.4vw;color:#808080;">{{ $New->NewTitle }}</p>
						  		<form action="{{ url('/showcontent/'.$New->Newid) }}">
						  			<button style="position: absolute;left:0;bottom:0;" id="readmore"><font style="font-size:12px;">อ่านต่อ</font></button>
						  		</form>
						  </div>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding:0;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banner">
					
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banner-content" style="text-align: center;">
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="content-first">
					<font id="header-banner">ทุกอุปกรณ์</font>
					<font id="des-banner">รองรับการใช้งานทุกอุปกรณ์</font>
					<img id="img-banner" src="{{ asset('/image/devices.png') }}">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="content-two">
					<font id="header-banner">สะดวก</font>
					<font id="des-banner">ใช้งานง่ายเข้าถึงทุกผู้ใช้</font>
					<img id="img-banner" src="{{ asset('/image/hourglass.png') }}">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="content-three">
					<font id="header-banner">อัตโนมัติ</font>
					<font id="des-banner">จัดการผู้ใช้งานที่ไม่เหมาะสม</font>
					<img id="img-banner" src="{{ asset('/image/automatic-brightness.png') }}">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="content-four">
					<font id="header-banner">มีประสิทธิภาพ</font>
					<font id="des-banner">ทุกห้องถูกใช้งานอย่างคุ้มค่า</font>
					<img id="img-banner" src="{{ asset('/image/seo-performance-marketing-graphic.png') }}">
				</div>
			</div>
		</div>
		
		<div clas="container" style="padding:0;">
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;margin-top:40px;margin-bottom:40px;">
				<span style="margin-top:-200px;height:200px;" class="anchor" id="section3"></span>
				<center><font id="headerSection">ภาพห้องศึกษา</font></center>
				<center><div class="divider" style="margin-bottom:15px;"></div></center>
				<center><font id="desSection">ตัวอย่างภาพห้องศึกษาที่เปิดให้บริการ</font></center>
				
				<div class="col-md-12 col-sm-12 col-xs-12" id="carousel" style="text-align:center;margin-top:40px;padding:0;padding-left:1vw;padding-right:1vw;width:100%;overflow:auto;white-space: nowrap;">
					<?php $single = 0 ?>
                    @foreach($Rooms as $Room)
                        @if($Room->RoomPeople == 1 and $single == 0)
                        	<img  src="{{ '/thumbnails/'.$Room->ImageName }}" id ="imgslide">
                        	<?php $single = 1 ?>
                        @endif
                        @if($Room->RoomPeople != 1)
                        	<img  src="{{ '/thumbnails/'.$Room->ImageName }}" id ="imgslide">
                        @endif
                    @endforeach
					

				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="margin-top:40px;padding-left:2vw;padding-right:0;">
					<a style="cursor: pointer;" class="pull-left" id="left-button"><img src="{{ asset('/image/left-arrow.png') }}" id="arrowLeft"></a>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="margin-top:40px;padding-right:2vw;padding-left:0;">
					<a style="cursor: pointer;" class="pull-right" id="right-button"><img src="{{ asset('/image/right-arrow.png') }}" id="arrowRight"></a>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer" id="section4">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 leftfooter" style="height:auto;">
				<button id="borderButton" style="margin-bottom:40px;font-size:17px !important;">วันทำการจองห้อง</button>
				<p style="color:white;">เปิดบริการ วันจันทร์-ศุกร์ เวลา 9.00–19.00 น. และ เสาร์-อาทิตย์ เวลา 9.00-16.30 น.</p>
				<p style="color:white;margin-top:30px;">ช่วงสอบจะขยายเวลาเปิดให้บริการเฉพาะห้องศึกษากลุ่ม ชั้น 2 และห้องศึกษาเดี่ยว ในวันจันทร์-ศุกร์ เวลา 9.00-21.30 น. และ วันเสาร์-อาทิตย์ เวลา 9.00-18.30 น.</p>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 rightfooter" style="height:auto;">
				<button id="borderButton" style="margin-bottom:40px;font-size:17px !important;">ติดต่อห้องสมุด</button>
				<font style="color:white;display:block;">Library Kasetsart University Sriracha Campus</font>
				@if(count($contact) >0)
                    @foreach($contact as $contact)
                    	<font style="color:white;display:block;margin-top:40px;">เว็บไซต์ : {{ $contact->WebName }}</font>
                    	<font style="color:white;display:block;">อีเมลล์ : libraryreservsrc@gmail.com</font>
                    	<font style="color:white;display:block;">โทรศัพท์ : {{ $contact->Phone }}</font>
                    @endforeach
                @else
                	<font style="color:white;display:block;margin-top:40px;">เว็บไซต์ : -</font>
                	<font style="color:white;display:block;">อีเมลล์ : libraryreservsrc@gmail.com</font>
                	<font style="color:white;display:block;">โทรศัพท์ : -</font>
                @endif
				
			    
				
			</div>
			<div id="desktopfooter">
				<font  style="margin-bottom:20px;width:100%;left:0;text-align:center;position:absolute;bottom:0;display:block;color:white;font-size:14px;">Copyright @ 2017, Library Reservation Powered By <font style="color:#F74443;">Computer Engineering-KUSRC</font></font>
			</div>
			<div id="mobilefooter">
				<font style="margin-bottom:20px;width:100%;left:0;text-align:center;position:absolute;bottom:0;display:block;color:white;font-size:14px;">Powered By <font style="color:#F74443;">CPE-KUSRC @ 2017</font></font>
			</div>
		</div>
	</div>

@endsection