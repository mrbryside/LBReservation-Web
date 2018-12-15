		<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
    <div>	
			  <br>
              <br>
              <h2 class="ui Left floated header"><font size = "5" color ="#828282">SINGLE</font><br><font size = "6" color ="#B92000">ROOMS</font></h2>
              <a id="roomtoggle" href="#">
                <div id = "link">
              	   <h2 class="ui right floated header"><br><font color = "#5B5B5B" size ="4" id ="toggletext">ห้องศึกษากลุ่ม<i class="arrow right icon"></i></font></h2>
                </div>
              </a>
              <div class="ui clearing divider"></div>
              @if(Session::has('flash_message2'))
                      <div class="alert alert-info"><em> <center><li>{!! session('flash_message2') !!}</li></center></em></div>
              @endif
              @if(Session::has('flash_message'))
                  <div class="alert alert-danger"><em><center><li>{!! session('flash_message') !!}</li></center></em></div></center>
              @endif
              @if (session('status'))
                  <div class="alert alert-info">
                      {{ session('status') }}
                  </div>
              @endif
              @if ($errors->has('ImageName'))
                <span class="help-block">
                    <div class="alert alert-danger"><em> <center><li>{{ $errors->first('ImageName') }}</li></center></em></div>
                </span>
              @endif
              @if(auth::user()->status == 1)
                  <div class="ui styled fluid accordion" id ="border">
                    <div class="title">
                      <i class="dropdown icon"></i>
                       <font color ="#AC2002">อัพเดทรูปแผนผังห้องศึกษาเดี่ยว</font>
                    </div>
                    <div class="content">
                        @if($Plan == null)
                          <form class="form-horizontal" action="{{ url('/singleplan') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                  <label class="col-md-5 control-label">Plan Image<font color ="red"></font></label>
                                  <div class="col-md-7">
                                      <input type="file" name="ImageName" required>
                                      (jpeg,jpg,png)
                                      <br>
                                      <button type="submit" class="btn btn-primary">
                                         <i class="write icon"></i>Submit
                                      </button>
                                  </div>
                                </div>
                           </form>
                        @else
                            <form class="form-horizontal" action="{{ url('/singleplan/'.$Plan->SinglePlanID) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                  <label class="col-md-5 control-label">Plan Image<font color ="red"></font></label>
                                  <div class="col-md-7">

                                      <input type="file" name="ImageName" required>
                                      (jpeg,jpg,png)
                                      <br>
                                      <button type="submit" class="btn btn-primary">
                                         <i class="write icon"></i>Submit
                                      </button>
                                  </div>
                                </div>
                           </form>
                        @endif
                    </div>
                 </div>
                 <br>
              @elseif(auth::user()->status != 1)
                <br>
              @endif
              @if ($admin == 1)
                <div class="ui four stackable cards" id = "card">
                 @foreach($Room as $Room)
                    @if( $Room->RoomPeople == 1)
                      <div class="ui link card" id = "border">
                          <div class="panel-heading"><img width ="22" height ="22" src="{{ asset('/image/notebook.png') }}">
                              <form class="pull-right" action="{{ url('/home/'.$Room->Roomid) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="image" src="{{ asset('/seimg/delete.ico') }}" width ="29" height ="26">
                              </form>
                                &nbsp;&nbsp;{{ $Room->RoomName }}
                            
                          </div>
                        <a class ="image" href="{{ '/thumbnails/'.$Room->ImageName }}" rel="lightbox"><img src="{{ '/thumbnails/'.$Room->ImageName }}"/></a>
                        <div class="content">
                            <span class ="pull-left"><b>Desciption&nbsp;:&nbsp;</b>{{ $Room->RoomDescription }}</span>
                            <br>
                          <span class ="pull-left"><b>Min People&nbsp;:&nbsp;</b>&nbsp;{{ $Room->RoomPeople }}</a>
                          <br>
                          <span class ="pull-left"><b>Floor&nbsp;:&nbsp;</b>{{ $Room->RoomFloor }}</a>
                          @if($Room->RoomStatus == 1)
                            <form action="{{ url('/home/open/'.$Room->Roomid.'/0') }}" method="post" onsubmit="return confirm('คุณต้องการ เปิดระบบจองห้องนี้ใช่หรือไม่?')">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <span><span class ="pull-left"><b>Status&nbsp;:&nbsp;</b></a><button class="ui button" id = "buttoncoloroff" onmouseover="this.style.background='#FA1F1F';" onmouseout="this.style.background='#FA3E3E';" type="submit"><font size="2" color ="#FAFAFA"><center>OFF</center></font></button></span>
                            </form>
                          @else
                            <form action="{{ url('/home/open/'.$Room->Roomid.'/1') }}" method="post" onsubmit="return confirm('คุณต้องการ ปิดระบบจองห้องนี้ใช่หรือไม่?')">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="_method" value="PUT">
                                      <span><span class ="pull-left"><b>Status&nbsp;:&nbsp;</b></a><button class="ui button" id = "buttoncoloron" onmouseover="this.style.background='#26a472';" onmouseout="this.style.background='#2AB27B';" type="submit"><font size="2" color ="#FAFAFA"><center>ON</center></font></button></span>
                          </form>
                          @endif
                        </div>
                        <div class="extra content">
                          <center>
                          <a>
                          <a href="{{ url('/home/'.$Room->Roomid) }}">
                          <span><button class="ui button" type="submit" style="width:70px;"><font size="2">Show</font></button></span></a>
                          <a href="{{ url('/home/'.$Room->Roomid.'/edit') }}">
                          <span><button class="ui button" type="submit" style="width:70px;"><font size="2">Edit</font></button></span></a>
                          </a>
                          </center>
                        </div>
                      </div>
                    @endif
                @endforeach
                </div>
                <br>
              @else
                <div class="ui four stackable cards" id="card2">
                 @foreach($Room as $Room)
                    @if($Room->RoomPeople ==1)
                    <div class="ui link card" id = "border">
                        <div class="panel-heading"><img width ="22" height ="22" src="{{ asset('/image/notebook.png') }}">&nbsp;{{ $Room->RoomName }}
                        </div>
                      <a class ="image" href="{{ '/thumbnails/'.$Room->ImageName }}" rel="lightbox"><img src="{{ '/thumbnails/'.$Room->ImageName }}"/></a>
                      <div class="content">
                          <span class ="pull-left"><b>Desciption&nbsp;:&nbsp;</b>{{ $Room->RoomDescription }}</span>
                          <br>
                        <span class ="pull-left"><b>Min People&nbsp;:&nbsp;</b>&nbsp;{{ $Room->RoomPeople }}</a>
                        <br>
                        <span class ="pull-left"><b>Floor&nbsp;:&nbsp;</b>{{ $Room->RoomFloor }}</a>
                      </div>
                      <div class="extra content">
                        <center>
                        <a>
                        <a href="{{ url('/home/'.$Room->Roomid) }}">
                        @if(auth()->user()->status == 0)
                          <span><button class="ui teal button" type="submit"><center><i class="write icon"></i><font size="2" color ="#FAFAFA">จองห้อง</font></center></button></span></a>
                          </center>
                        @else
                          <span><button class="ui teal button" type="submit"><center><font size="2" color ="#FAFAFA"><i class="sign in icon"></i>Show</font></center></button></span></a>
                          </center>
                        @endif
                      </div>
                    </div>
                    @endif
                @endforeach
                </div>
                <br>
              @endif
          </div> 
     </div>


 <script>
    var width = $(window).width();
    if(width > 1199){
      $("#card").removeClass();
      $("#card").addClass("ui four stackable cards");
      $("#card2").removeClass();
      $("#card2").addClass("ui four stackable cards");
    }
    if(width <= 1199){
      $("#card").removeClass();
      $("#card").addClass("ui three stackable cards");
      $("#card2").removeClass();
      $("#card2").addClass("ui three stackable cards");
    }
    if(width <= 991){
      $("#card").removeClass();
      $("#card").addClass("ui two stackable cards");
      $("#card2").removeClass();
      $("#card2").addClass("ui two stackable cards");
     }

    $(window).on('resize', function(){
       if($(this).width() != width){
          width = $(this).width();
          if(width > 1199){
            $("#card").removeClass();
            $("#card").addClass("ui four stackable cards");
            $("#card2").removeClass();
            $("#card2").addClass("ui four stackable cards");
          }
          if(width <= 1199){
            $("#card").removeClass();
            $("#card").addClass("ui three stackable cards");
            $("#card2").removeClass();
            $("#card2").addClass("ui three stackable cards");
           }
           if(width <= 991){
            $("#card").removeClass();
            $("#card").addClass("ui two stackable cards");
            $("#card2").removeClass();
            $("#card2").addClass("ui two stackable cards");
           }
       }
    });
    $('#link').hide();
       setTimeout(function() { 
           $('#link').fadeIn("slow"); 
        }, 250);
     $('#roomtoggle').on('click', function(){
          $('#sharedroom')
                .transition({
                  animation  : 'scale out',
                })
            ;
          $('#sharedroom').load("{{asset('sharedroom')}}", function() {
                $('#sharedroom')
                    .transition({
                      animation  : 'scale in',
                      duration   : '0.4s',
                    })
                ;
          });
      });
      $('.ui.accordion')
        .accordion()
      ;

 </script>