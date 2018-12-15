<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @yield('title')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="images/bs.png">
    

    
    <script src="{{ asset('dist/js/lightbox-plus-jquery.min.js') }}"></script>
    <link rel="stylesheet" href= "{{ asset('dist/css/lightbox.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/semantic.min.css') }}">
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  
  <link href="{{ asset('datetime/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

  <script src="{{ asset('datetime/build/js/bootstrap-datetimepicker.min.js') }}"></script>  
  
  <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Kanit');

        html *{
            font-family: 'Kanit', sans-serif; 
        } 
        .navbar-default .navbar-toggle:focus, .navbar-default .navbar-toggle:hover{     
            background-color:#575757 !important;    
        }
        #myBtn {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            bottom: 20px; /* Place the button at the bottom of the page */
            right: 30px; /* Place the button 30px from the right */
            z-index: 99; /* Make sure it does not overlap */
            border: none; /* Remove borders */
            outline: none; /* Remove outline */
            background-color: #575757 !important; /* Set a background color */
            color: white; /* Text color */
            cursor: pointer; /* Add a mouse pointer on hover */
            padding: 15px; /* Some padding */
            border-radius: 10px; /* Rounded corners */
        }

        .icon-bar{
            background-color:white !important;
        }

        #myBtn:hover {
            background-color: #575757 !important; /* Add a dark-grey background on hover */
            
        }
        body{
            background-color:#EEF3F7 !important;
        }
        #finishReserve{
            font-size:0.8em;
        }
        #howtomodal{
           position: relative;
           overflow-y: scroll !important;
        }
        #rulemodal{
           position: relative;
           overflow-y: scroll !important;
        }
        #textrule{ display:table; margin:0 auto;}
        #texthowto{ display:table; margin:0 auto;}
        #userlogin{ display:table; margin:0 auto;}
        .unread{
              background-color:#EDF2FA;
        }
        .scrollable-menu {
            height: auto;
            width: auto;
            max-width: 500px;
            max-height: 370px;
            overflow-x: hidden;
        }
        .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
            color: #C9C9C9;  /*Sets the text hover color on navbar*/
        }
        #phonetext{
            display:none;
        }
        #textexam{
            font-size:1em;
        }
        @media(max-width:768px){
            .navbar.navbar-default .navbar-nav .open .dropdown-menu > li > a {
                color: #FFF;
            }
            #howtomodal{
               position: relative;
            }
            #rulemodal{
               position: relative;
            }
            .unread{
              background-color:#9B1C00;
            }
            #phonetext{
                display:block;
            }
            #finishReserve{
                font-size:0.6em;
            }
            #toggletext{
                font-size:0.95em;
            }
            #textadmin{
                font-size:0.8em;
            }
            #textnisit{
                font-size:0.8em;
            }
            #textexam{
                font-size:0.9em;
            }
        }
        @media(max-width:340px){
            .navbar.navbar-default .navbar-nav .open .dropdown-menu > li > a {
                color: #FFF;
            }
            #howtomodal{
               position: relative;
            }
            #rulemodal{
               position: relative;
            }
            .unread{
              background-color:#9B1C00;
            }
            #phonetext{
                display:block;
            }
            #finishReserve{
                font-size:0.6em;
            }
            #toggletext{
                font-size:0.95em;
            }
            #textadmin{
                font-size:0.8em;
            }
            #textnisit{
                font-size:0.9em;
            }
            #textexam{
                font-size:0.9em;
            }
        }
        .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active >   
             a:hover, .navbar-default .navbar-nav > .active > a:focus {
            color: white; /*BACKGROUND color for active*/
            background-color: #030033;
        }

          .navbar-default {
            background-color: #575757;
            border:0;
            box-shadow: 0 4px 6px 0 rgba(0,0,0,0.2);
        }

        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            color: #262626;
           text-decoration: none;
           background-color: #66CCFF;  /*change color of links in drop down here*/
        }
        .nav > li > a:hover,
        .nav > li > a:focus {
            text-decoration: none;
            background-color: silver; /*Change rollover cell color here*/
        }
        .navbar-default .navbar-nav > li > a {
            color: white; /*Change active text color here*/
        }        
        #allmenu{
            display: none;
        }
        #table{
            display: none;
        }
        #form{
            display: none;
        }
        #hideform{
            display: none;
        }
        #closeform{
            display: none;
        }
        .navbar-fixed-bottom{
            display: none;
        }
        #buttoncoloroff{
            background-color: #FA3E3E;
        }
        #buttoncoloron{
            background-color: #2AB27B;
        }
        #bar{
            display: none;
        }
        .danger{
          background-color: #FA3E3E;
        }
        .table-responsive{
            overflow-x: auto;
           -webkit-overflow-scrolling: touch;
       }
       #ssss{
            margin-left: 5px;
            margin-right: 5px;
       }
       .user-row {
            margin-bottom: 14px;
        }

        .user-row:last-child {
            margin-bottom: 0;
        }

        .dropdown-user {
            margin: 13px 0;
            padding: 5px;
            height: 100%;
        }

        .dropdown-user:hover {
            cursor: pointer;
        }

        .table-user-information > tbody > tr {
            border-top: 1px solid rgb(221, 221, 221);
            border-color: #8ECDEE;
        }

        .table-user-information > tbody > tr:first-child {
            border-top: 0;
        }


        .table-user-information > tbody > tr > td {
            border-top: 0;
        }
        .toppad
        {margin-top:20px;
        }
        #userbody{
            background-color: #ECF0F1;
        }
        #userfooter{
            background-color: #282828;
            height:50px;
        }
        @yield('css')   
    </style>
    @yield('stylesheets')
</head>
<body id ="bodycolor">
    <div id="loading">
    </div> 
    <div id="app">
        @yield('navbar')
        @yield('content')
    </div> 
    <br>
    <center>
    <div class="navbar-fixed-bottom" id="para2">
        <i class="wizard icon"></i>
        <font size ="2"> Powered by CPE-KUSRC © 2017</font>         
    </div>
    </center>
    <!-- Scripts -->
    <script src="{{ asset('semantic/semantic.min.js') }}"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>

    <script type="text/javascript">
        @yield('loadtable')
        $(document).ready( function() {
            $('#allmenu').transition('scale');
            $('#table')
              .transition({
                animation  : 'fade down in',
                duration   : '0.4s',
              })
            ;
            $('#form')
              .transition({
                animation  : 'fly up',
                duration   : '1s',
              })
            ;
            $('#bar')
              .transition({
                animation  : 'scale',
                duration   : '0.6s',
              })
            ;

         });
        function playSound() {
          var sound = document.getElementById("audio");
          sound.play();
        }
        $(".navbar-fixed-bottom").fadeToggle();

        @yield('script')
        
    </script>
    <script src="/StreamLab/StreamLab.js"></script>
    <script>
        var message, ShowDiv = $('#showNotification'), count = $('#count'), c ,run = 0;
        var slh = new StreamLabHtml();
        var sls = new StreamLabSocket({
            appId:"{{ config('stream_lab.app_id') }}",
            channelName:"test",
            event:"*"
        });     
        sls.socket.onmessage = function(res){
            slh.setData(res);
            if(slh.getSource() == 'messages'){
                c = parseInt(count.html());
                run = 1;
                count.html(c+1);
                message = slh.getMessage();
                ShowDiv.prepend('<li><a href="{{ url('/reservationsearch') }}"  class ="unread"><img width ="10" height ="10" src="{{ asset('/seimg/jood.png') }}">&nbsp;'+message+'</a></li>');
                @yield('loadtableinsert')
            }
            if(run==1){
                $('#notification').each(function(){
                    $(this).hide();
                    $(this).transition('jiggle')
                    playSound();
                });
                run = 0;
            }
        }
        $('#notification').on('click', function(){
            $.get('/MarkAllSeen/', function(){});
            count.html(0);
        });
    </script>


    
    
</body>
</html>
