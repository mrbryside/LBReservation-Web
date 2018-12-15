<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newmodel;
use \Auth;

class ShowNewController extends Controller
{
    public function show($id){
        $New = Newmodel::find($id);
        if($New == null){
            return redirect()->back();
        }
        return view('news')->with('New',$New);
    }
}
