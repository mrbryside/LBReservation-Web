<!DOCTYPE html>
<html>
<head>
	@yield('title')
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<!-- scripts -->


	<link rel="stylesheet" type="text/css" href="{{ asset('/css/newstyle.css') }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
	@yield('content')


<script src="{{ asset('js/js/particles.js') }}"></script>
<script src="{{ asset('js/js/app.js') }}"></script>
<script>
  $('#right-button').click(function() {
    var width = window.innerWidth;
    event.preventDefault();
    var maxScrollLeft = $('#carousel').get(0).scrollWidth - $('#carousel').get(0).clientWidth;
    console.log(maxScrollLeft);
    if($('#carousel').get(0).scrollLeft < maxScrollLeft){
      if(width > 768){
        $('#carousel').animate({
          scrollLeft: '+='+maxScrollLeft/1.5+'px'
        }, "smooth");
      }
      else if(width <=768){
        $('#carousel').animate({
          scrollLeft: '+='+maxScrollLeft/2.5+'px'
        }, "smooth");
      }
    }
    else{
      $('#carousel').animate({
        scrollLeft:0
      }, "smooth");
    }
  });
  $('#left-button').click(function() {
    var width = window.innerWidth;
    event.preventDefault();
    var maxScrollLeft = $('#carousel').get(0).scrollWidth - $('#carousel').get(0).clientWidth;
    console.log(maxScrollLeft);
    if($('#carousel').get(0).scrollLeft <= 0){
      
      $('#carousel').animate({
        scrollLeft:maxScrollLeft
      }, "smooth");
    }
    else{
      if(width > 768){
        $('#carousel').animate({
          scrollLeft: '-='+maxScrollLeft/1.5+'px'
        }, "smooth");
      }
      else if(width <=768){
        $('#carousel').animate({
          scrollLeft: '-='+maxScrollLeft/2.5+'px'
        }, "smooth");
      }
    }
  });
	$( "#moreClick" ).click(function() {
	  $( "#moreDown" ).toggle();
	  $( "#moreUp" ).toggle();
    $("#allcard_id").css("overflow", "auto");
    var width = window.innerWidth;
    if(width >= 991){ 
      $("#allcard_id").css("max-height", "320px");
    }
    if(width < 991){
      $("#allcard_id").css("max-height", "620px");
    }
    if(width <= 487){
      $("#allcard_id").css("max-height", "520px");
    }
    if(width <= 379){
      $("#allcard_id").css("max-height", "500px");
    }
	});
	$( "#moreClick2" ).click(function() {
    event.preventDefault();
	  $( "#moreDown" ).toggle();
	  $( "#moreUp" ).toggle();
    $("#allcard_id").css("overflow", "hidden");
    $('#allcard_id').animate({
        scrollTop: 0
      }, "smooth");
    var width = window.innerWidth;
    if(width >= 991){ 
      $("#allcard_id").css("max-height", "290px");
    }
    if(width < 991){
      $("#allcard_id").css("max-height", "580px");
    }
    if(width < 487){
      $("#allcard_id").css("max-height", "480px");
    }
    if(width <= 379){
      $("#allcard_id").css("max-height", "460px");
    }
	});
  $( "#navbarLink" ).click(function() {
    var width = window.innerWidth;
    if(width <= 767){ 
      $('.navbar-toggle').click();
    }
  });
  $( "#navbarLink2" ).click(function() {
     var width = window.innerWidth;
     if(width <= 767){ 
       $('.navbar-toggle').click();
     }
  });
  $( "#navbarLink3" ).click(function() {
     var width = window.innerWidth;
     if(width <= 767){ 
       $('.navbar-toggle').click();
     }
  });
  $( "#navbarLink4" ).click(function() {
     var width = window.innerWidth;
     if(width <= 767){ 
       $('.navbar-toggle').click();
     }
  });
  $("li").click(function() {
      // remove classes from all
      $("li").removeClass("active");
      // add class to the one we clicked
      $(this).addClass("active");
   });
</script>
<script>
$(document).ready(function(){
  $(document).scroll(function(){
      var st = $(this).scrollTop();
      var one = 1;
      if(st < 100) {
        $("#navbarMenu").fadeIn("slow");
        $("#allTextWelcome").css("margin-top","0");
        $("#navbarMenu").removeClass('navbar-fixed-top');
      }
      if(st > 130 && st <=180) {
        $("#navbarMenu").hide();
        $("#allTextWelcome").css("margin-top","3vw");
      }
      if(st > 180) {
          $("#navbarMenu").addClass('navbar-fixed-top');
          $("#navbarMenu").fadeIn("slow");
      }

  });
  // Add smooth scrolling to all links
  $("a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "" && this.hash !== "#section1" && this.hash !== "#section2" && this.hash !== "#section3") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});
</script>
</body>
</html>
