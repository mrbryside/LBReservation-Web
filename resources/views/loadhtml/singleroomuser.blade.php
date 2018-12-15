	<div id = "load">
		  <br>
          <br>
            <h2 class="ui left floated header"><font id="singletext" size = "5" color ="#828282">SINGLE</font><br><font id="roomtext" size = "6" color ="#B92000">ROOMS</font></h2>
              <a id="roomtoggle" href="#">
                <div id = "link">
                   <h2 class="ui right floated header"><br><font color = "#5B5B5B" size ="4" id ="toggletext">ห้องศึกษากลุ่ม<i class="arrow right icon"></i></font></h2>
                </div>
              </a>
            <div class="ui clearing divider"></div>
            	  <br>
                    @if(Session::has('flash_message'))
                      <div class="alert alert-danger"><em> <center><li>{!! session('flash_message') !!}</li></center></em></div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if($PlanImage != null)
                        <center><img class="img-responsive" id = "border" src="{{ '/seimg/'.$PlanImage->ImageName }}" ></center>
                    @else
                        <center><img class="img-responsive" id = "border"></center>
                    @endif
                    <br>
                    
                    @if($RoomImage != null)
            			       <center><a class ="btn btn-primary" id = "border" href="{{ '/thumbnails/'.$RoomImage->ImageName }}" rel="lightbox"><i class="film icon"></i>&nbsp;ภาพห้องศึกษาเดี่ยว</a></center>
                   	@else
                   		   <center><a class ="btn btn-primary" id = "border"><i class="film icon"></i>&nbsp;ภาพห้องศึกษาเดี่ยว</a></center>
                   	@endif
                    
                </div>
            </div>
	            <h2 class="ui left floated header"><br><br><font id="formtext" size = "6" color ="#B92000">FORM</font><br> <font id="reservetext" size = "5" color ="#828282">RESERVATION</font></h2>
	            <div class="ui clearing divider"></div>
	            <div class="ui raised segment">
	                <br>
	                <br>
	                <form action="{{ url('/singletoroom') }}" method="get" class="form-inline"> 
	                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                    <center>
	                    <font size ="2">
	                    <div class ="form-group">
	                      <label for="email" class="control-label">เลือกห้อง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

	                      <select  class ="form-control" id="question" class="drop" name="question" required auto focus>
	                        <option value="" selected disabled>Please Select</option>
	                          @foreach($Room as $Room)
	                              @if($Room->RoomPeople == 1)
	                                <option value="{{ $Room->Roomid }}">{{ $Room->RoomName }}</option>
	                              @endif
	                          @endforeach
	                      </select>
	                    </div>
	                    </font>
	                        <button class="btn btn-success form-control" type="submit">จองห้อง</button>
	                        
	                    </center>        
	                <form>
	                <br>
	                <br>
	            </div>
	            <br>
    </div>

  <script>
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
		    // This gets executed when the content is loaded
	        $('#sharedroom')
	            .transition({
	              animation  : 'scale in',
	              duration   : '0.4s',
	            })
	        ;
		});
        
    });
 </script>