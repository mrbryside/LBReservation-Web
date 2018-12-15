<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newmodel;
use App\Contact;
use App\Room;
use \Auth;

class IndexController extends Controller
{
    public function index(){
        $New = Newmodel::get();
        $Rooms = Room::get();
        $contact = Contact::get();
        return view('index')->with('New',$New)
        					->with('contact',$contact)
        					->with('Rooms',$Rooms);
    }
    public function main(){
        $New = Newmodel::orderBy('created_at', 'desc')->get();
        $Rooms = Room::get();
        $contact = Contact::get();
        return view('main')->with('New',$New)
                            ->with('contact',$contact)
                            ->with('Rooms',$Rooms);
    }
    public function error(){
    	return view('errors.404');
    }
}
