@extends('layouts.newlayout')


@section('title')
<title>Library Reservation</title>
<link rel="shortcut icon" href="{{ asset('/images/bs.png') }}">
@endsection



@section('content')
	<div style="position: relative;">
		<div class="container" style="display:inline;position: absolute;top: 0;left: 0;width:100%;height:30px;">
			<nav class="navbar navbar-fixed-top">
				<div class="navbar-header">
				  <a href ="{{ url('/#section1') }}">
				  	<font class="header-red"><img src="{{ asset('/image/open-book.png') }}" class="bookLogo">LBReservation</font>
				  </a>
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <img src="{{ asset('/image/line-menu.png') }}" class="burgerMenu">               
			      </button>
			    </div>
			    <div class="navbar-header">
				    <div class="collapse navbar-collapse" id="myNavbar" style="padding:0;margin:0;border:0;">
					    <ul class="nav navbar-nav">
					      <li><a href ="{{ url('/#section1') }}">หน้าหลัก</a></li>
					      <li><a href ="{{ url('/#section2') }}">ประชาสัมพันธ์</a></li>
					      <li><a href ="{{ url('/#section3') }}">ภาพห้องศึกษา</a></li>
					      <li><a id ="navbarLink" href ="#section4">ติดต่อห้องสมุด</a></li>
					    </ul>
					 </div>
				</div>
			</nav>	
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="box">
			<font id="headerNews">{{ $New->NewTitle }}</font>
			
			<div class="dividerNews" style="margin-top:10px;width:100%;margin-bottom:13px;"></div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:70px;">
			<div class="row">
				<div id="sheet">
					<div style="padding:0;width:auto;display:inline-block;">
						<div id="profileBox" style="border-radius:80px;background-color:#67DCAD !important;">
							<center><img id="profileImage"src="{{ asset('/image/girl.png') }}"></center>

						</div>
					</div>
					<div id="postInformation">
						<font style="">โพสโดย : เจ้าหน้าที่ห้องสมุด</font>
						<font style="display:block;margin-top:10px;">โพสเมื่อ : {{ $New->created_at->format('d M Y') }}</font>
						<font style="display:block;margin-top:10px;">แก้ไขเมื่อ : {{ $New->created_at->format('d M Y') }}</font>
						
						
						<div id="allSocial">
							<a  target="_blank" href="https://www.facebook.com/libsiracha/"><img id="socialIconFirst" href="https://www.facebook.com/libsiracha/"  src="{{ asset('/image/facebook.png') }}"></a>
							<a href="#"><img id="socialIcon" onclick="alert('libraryreservsrc@gmail.com')" src="{{ asset('/image/email.png') }}"></a>
							<a href="#"><img onclick="alert('038-354580-4 ต่อ 2730')" id="socialIcon" src="{{ asset('/image/mobile-phone.png') }}"></a>		
							<a href="#"><img id="socialIcon" onclick="alert('http://lib.vit.src.ku.ac.th/')" src="{{ asset('/image/world-wide-web.png') }}"></a>
									
							
							
						</div>
					</div>
					<div class="dividerContentNews"></div>
					<p id="paragraph">{!!$New->NewParagraph1 !!}</p>

					<form action="{{ url('/#section2') }}">
						<button style="margin-top:50px;float:right" id="readmore"><font style="font-size:12px;">ย้อนกลับ</font></button>
					</form>
					<br>
					<br>
					<br>
					<br>

				</div>



			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer" id="section4">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 leftfooter" style="height:auto;">
				<button id="borderButton" style="margin-bottom:40px;font-size:17px !important;">วันทำการจองห้อง</button>
				<p style="color:white;">เปิดบริการ วันจันทร์-ศุกร์ เวลา 9.00–19.00 น. และ เสาร์-อาทิตย์ เวลา 9.00-16.30 น.</p>
				<p style="color:white;margin-top:30px;">ช่วงสอบจะขยายเวลาเปิดให้บริการเฉพาะห้องศึกษากลุ่ม ชั้น 2 ในวันจันทร์-ศุกร์ เวลา 9.00-22.00 น. และ วันเสาร์-อาทิตย์ เวลา 9.00-18.30 น.</p>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 rightfooter" style="height:auto;">
				<button id="borderButton" style="margin-bottom:40px;font-size:17px !important;">ติดต่อห้องสมุด</button>
				<font style="color:white;display:block;">Library Kasetsart University Sriracha Campus</font>
				<font style="color:white;display:block;margin-top:40px;">เว็บไซต์ : http://lib.vit.src.ku.ac.th/</font>
			    <font style="color:white;display:block;">อีเมลล์ : libraryreservsrc@gmail.com</font>
				<font style="color:white;display:block;">โทรศัพท์ : 038-354580-4 ต่อ 2730</font>
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