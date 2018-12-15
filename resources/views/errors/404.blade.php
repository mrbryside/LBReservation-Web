@extends('layouts.app')

@section('title')
<title>404 Error</title>
@endsection

@section('content')
<div class="container" id ="allmenu">
    <div class="row">
        <div class="col-md-20 col-md-offset-0">
            <br>
            <br>
            <center><h1><b><font size = "12" color ="#B92000">&nbsp;&nbsp;Opps...Error</font></b></h1></center>
            <br>

            <center><img class="img-responsive" src="{{ '/seimg/404.png' }}" ></center>
            <br>
            <center><a href="{{ url('/') }}"><button class ="ui teal button"><font size ="2">Back</font></button></a></center>
        </div>
    </div>
    <br>
</div>
@endsection
