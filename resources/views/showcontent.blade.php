@extends('layouts.app')

@section('title')
<title>News</title>
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
                    <a class="navbar-brand" href = "{{ url('/showcontent/'.$New->Newid) }}">
                        <font color ="white" size="3"><b><img style ="width:40px; height:27px;" src="{{ asset('/seimg/megaphone.png') }}">&nbsp;&nbsp;NEWS</b></font>
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
                          <li><a href="{{ url('/#service') }}"><i class="home icon"></i><b>Home</b></a></li>
                    </ul>
                </div>
            </div>
        </nav>
@endsection

@section('content')
<button onclick="topFunction()" id="myBtn" title="Go to top">Back</button>
<div class="container" id ="allmenu">
    <div class="row">
        <div class="col-md-20 col-md-offset-0">
            <br>
            <br>
            <br>
            <br>
            <h2 class="ui left floated header"><font size = "5" color ="#828282">&nbsp;&nbsp;CONTENT</font><br> <font size = "6" color ="#B92000">&nbsp;&nbsp;NEWS</font></h2>
            <a href ="{{ url('/#service') }}">
              <h2 class="ui right floated header"><br><font size = "4" color ="#5B5B5B">BACK<i class="reply icon"></i></font></h2>
            </a>
            <div class="ui clearing divider"></div>
              <div class="ui raised segment">
                  <center><br>
                  <h2><b><font color="#000"><i class="newspaper icon"></i>&nbsp;{{ $New->NewTitle }}</font></b></h2>
                  </center>
                  <br>
                  <div class="ui clearing divider" id="divide"></div>
                  <font size = "4">
                  <p>
                  {!!$New->NewParagraph1 !!}</p>
                  <br></font>
              </div>
          </div>
        </div>
        <br>
        <br>
        <br>
</div>
@endsection

@section('css')
    img{
     max-width: 100%;
    }
    p { margin-left:3em; margin-right:3em; }
    #divide { margin-left:5em; margin-right:5em; }
    iframe {
      width:70%;    /* as desired */
      height:50vh;    /* as desired */
      display:block;
      margin:0px auto
    }
    @media(max-width:500px){
      iframe {
        width:100%;    /* as desired */
        height:30vh;    /* as desired */
        display:block;
        margin:0px auto
      }
      img{
         max-width: 100%;
         height:auto;
      }
      p { margin-left:0.2em; margin-right:0.2em; }
      #divide { margin-left:0.2em; margin-right:0.2em; }
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

  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
            $('#myBtn').fadeIn();
        } else {
            $('#myBtn').hide();
        }
      }
  }

  function topFunction() {
      window.location.href = '/#service';
  }
@endsection