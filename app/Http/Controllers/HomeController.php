<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Room;
use App\Plan;
use App\Contact;
use App\Usermodel;
use Illuminate\Pagination\Paginator;
use App\Reservation;
use App\Reservationstore;
use App\Notifications\AddReservation;
use App\User;
use App\Ban;
use App\Close;
use App\Rule;
use App\RuleItem;
use Illuminate\Support\Facades\Input;
use Notification;
use Image;
use Mail;
use Carbon\Carbon;
use StreamLab\StreamLabProvider\Facades\StreamLabFacades;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $admin = Auth::user()->status;
        $singleuser = 0;
        $Room = Room::where('RoomPeople','>',0)->orderBy('RoomFloor')->get();    
        $examcheck = 0;
        $closeExam = Close::where('CloseExam',1)->first();
        if($closeExam != null){
            $examcheck = 1;
        }

        $rule = Rule::where('RuleType','rule')->first();
        if($rule != null){
            $ruleItems = RuleItem::where('rule_id',$rule->rule_id)->get();

        }
        else{
            $ruleItems = 'no';
            $rule = 'no';
        }

        $howto = Rule::where('RuleType','้howto')->first();
        if($howto != null){
            $howtoItems = RuleItem::where('rule_id',$howto->rule_id)->get();
        }
        else{
            $howtoItems = 'no';
            $howto = 'no';
        }

        return view('home.home')->with('admin',$admin)
                                ->with('Room',$Room)
                                ->with('singleuser',$singleuser)
                                ->with('examcheck',$examcheck)
                                ->with('rule',$rule)
                                ->with('ruleItems',$ruleItems)
                                ->with('howto',$howto)
                                ->with('howtoItems',$howtoItems);
    } 
    public function indexsingleuser(){
        $admin = Auth::user()->status;
        $singleuser = 0;
        $Room = Room::where('RoomPeople','>',0)->orderBy('RoomFloor')->get();    
        $examcheck = 0;
        $closeExam = Close::where('CloseExam',1)->first();
        if($closeExam != null){
            $examcheck = 1;
        }

        $rule = Rule::where('RuleType','rule')->first();
        if($rule != null){
            $ruleItems = RuleItem::where('rule_id',$rule->rule_id)->get();

        }
        else{
            $ruleItems = 'no';
            $rule = 'no';
        }

        $howto = Rule::where('RuleType','้howto')->first();
        if($howto != null){
            $howtoItems = RuleItem::where('rule_id',$howto->rule_id)->get();
        }
        else{
            $howtoItems = 'no';
            $howto = 'no';
        }

        return view('home.home')->with('admin',$admin)
                                ->with('Room',$Room)
                                ->with('singleuser',$singleuser)
                                ->with('examcheck',$examcheck)
                                ->with('rule',$rule)
                                ->with('ruleItems',$ruleItems)
                                ->with('howto',$howto)
                                ->with('howtoItems',$howtoItems);
    }
    public function adminindex(){
        $admin = Auth::user()->status;
        if($admin == 0){
           return redirect()->to('/home');
        }
        $adminInfo = Auth::user();
        $today = Carbon::today();
        $Reservations = Reservation::orderBy('ReservationStart')                              
                                ->where('ReservationStart', '<=', Carbon::tomorrow())
                                ->where('ReservationStart','>=', Carbon::today())
                                ->where('ReservationEnd','>=', Carbon::now())
                                ->paginate(10);
        if($admin){
           return view('home.adminindex')->with('Reservations',$Reservations)
                                         ->with('adminInfo',$adminInfo)
                                         ->with('today',$today);
        }
        
    }
    public function userlogin(){
        $status = Auth::user()->status;
        if($status == 0){
            if(strlen(Auth::user()->StudentID) != 10){
                return redirect()->to('/writeuser');
            }
            else{
                return redirect()->to('/home');
            }
            
        }
        else{
            return redirect()->to('/adminindex');
        }

    }
    public function writeuser(){
        if(strlen(Auth::user()->StudentID) == 10){
            return redirect()->back();
        }
        $admin = Auth::user()->status;
        return view('home.writeuser')->with('admin',$admin);

    }
    public function updateuser($id,Request $request){
        $this->validate($request,[
                'Faculty'=>'required',
                'Phone' => 'required|string|min:9|max:10',
                'StudentID' => 'required|numeric|digits:10|unique:users',
        ],

        [   
            'StudentID.required' => 'โปรดกรอก Stundent ID!',
            
        ]);
        
        $user = User::find($id);
        if($user == null){
            return redirect()->back();
        }
        $user->Faculty = $request->input('Faculty');
        $user->Phone = $request->input('Phone');
        $user->StudentID = $request->input('StudentID');
        $user->save();

        return redirect()->to('/userlogin');

    }
    public function loadtableadminindex(){
        $Reservations = Reservation::orderBy('ReservationStart')                              
                                ->where('ReservationStart', '<=', Carbon::tomorrow())
                                ->where('ReservationStart','>=', Carbon::today())
                                ->where('ReservationEnd','>=', Carbon::now())
                                ->get();
        return view('loadhtml.loadtable')->with('Reservations',$Reservations);
    }
    public function single(){
        $admin = Auth::user()->status;
        $Room = Room::where('RoomPeople',1)->get();
        $plan = Plan::first();
        return view('home.single')->with('admin',$admin)
                                  ->with('Room',$Room)
                                  ->with('Plan',$plan);
    }
    public function loadsingleroom(){
        $admin = Auth::user()->status;
        $Room = Room::where('RoomPeople',1)->get();
        $plan = Plan::first();
        return view('loadhtml.singleroom')->with('admin',$admin)
                                          ->with('Room',$Room)
                                          ->with('Plan',$plan);
    }
    public function loadsingleroomuser(){
        $admin = Auth::user()->status;
        $Room = Room::where('RoomPeople',1)->get();
        $RoomImage = Room::where('RoomPeople',1)->first();
        $plan = Plan::first();
        return view('loadhtml.singleroomuser')->with('admin',$admin)
                                              ->with('Room',$Room)
                                              ->with('RoomImage',$RoomImage)
                                              ->with('PlanImage',$plan);
    }
    public function loadsingleroomform(){
        $admin = Auth::user()->status;
        return view('loadhtml.singleroomuserform')->with('admin',$admin);
    }
    public function loadsharedroom(){
        $admin = Auth::user()->status;
        $Room = Room::where('RoomPeople','>',1)->orderBy('RoomFloor')->get();
        return view('loadhtml.sharedroom')->with('admin',$admin)
                                      ->with('Room',$Room);
    }
    public function singletoroom(){
        $admin = Auth::user()->status;
        $home = 1;
        $Rooms = Room::get();
        $RoomOptionID = Input::get('question');
        if($RoomOptionID != ""){
            return redirect()->to('/home/'.$RoomOptionID);
        }
        else{
            return redirect()->back();
        }
    }
    public function create(){
        $admin = Auth::user()->status;
        return view('home.create')->with('admin',$admin);
    }
    public function show($id,Request $request){
        $s = $request->input('s');
        $today = Carbon::now();
        $admin = Auth::user()->status;
        $Room = Room::find($id);
        if($Room == null){
            return redirect()->back();
        }
        $Reservations = Reservation::orderBy('ReservationStart')
                                ->where('room_id',$id)
                                ->where('ReservationEnd','>=',$today)
                                ->paginate(10);
        
        return view('home.show')->with('Room',$Room)
                                ->with('Reservations',$Reservations)
                                ->with('admin',$admin);
    }
    public function updateStatus($id,$id2){
        $update = Room::find($id);
        if($update == null){
            return redirect()->back();
        }
        if($id2==1){
            $update->RoomStatus = 1;
            $update->save();
        }
        else{
            $update->RoomStatus = 0;
            $update->save();
        }
        if($update->RoomPeople > 1){
            return redirect()->to('/home');
        }
        else{
            return redirect()->to('/single');
        }
        
    }
    public function store(Request $request){
        $this->validate($request,[
                'RoomName'=>'required|max:255',
                'RoomDescription'=>'required|max:255',
                'RoomFloor'=>'required|integer|between:1,10',
                'RoomPeople'=>'required|integer|between:1,50',
                'ImageName'=>'required|mimes:jpeg,jpg,png|max:4096',
        ]);

        $store = new Room;
        $store->RoomName = $request->input('RoomName');
        $store->RoomDescription = $request->input('RoomDescription');
        $store->RoomPeople = $request->input('RoomPeople');
        $store->RoomFloor = $request->input('RoomFloor');
        if(Input::hasFile('ImageName')){
            $Room=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/thumbnails/';
            $image->resize(650,550);
            $image->save($path.$Room->getClientOriginalName());

        }
        $store->ImageName = $Room->getClientOriginalName();
        $store->ImageSize = $Room->getClientsize();
        $store->ImageType = $Room->getClientMimeType();
        $store->save();

        if($store->RoomPeople > 1){
            return redirect()->to('/home');
        }
        else{
            return redirect()->to('/single');
        }
        
    }
    public function user(Request $request){
        $User = Usermodel::get();
        return view('home.user')->with('User',$User);
    }

    public function edit($id){
        $Room = Room::find($id);
        if($Room == null){
            return redirect()->back();
        }
        $admin = Auth::user()->status;
        return view('home.edit')->with('Room',$Room)
                                ->with('admin',$admin);
    }
    public function update($id, Request $request){
        $this->validate($request,[
                'RoomName'=>'required|max:255',
                'RoomDescription'=>'required|max:255',
                'RoomFloor'=>'required|integer|between:1,10',
                'RoomPeople'=>'required|integer|between:1,50',
                'ImageName'=>'mimes:jpeg,jpg,png|max:4096',
        ]);
        $update = Room::find($id);
        if($update == null){
            return redirect()->back();
        }
        $update->RoomName = $request->input('RoomName');
        $update->RoomDescription = $request->input('RoomDescription');
        $update->RoomPeople = $request->input('RoomPeople');
        $update->RoomFloor = $request->input('RoomFloor');
        if(Input::hasFile('ImageName')){
            $Room=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/thumbnails/';
            $image->resize(650,550);
            $image->save($path.$Room->getClientOriginalName());

        }
        else{
            $update->save();
            return redirect()->to('/home');
        }
        $update->ImageName = $Room->getClientOriginalName();
        $update->ImageSize = $Room->getClientsize();
        $update->ImageType = $Room->getClientMimeType();
        $update->save();



        return redirect()->to('/home');
    }

    public function destroy($id){
        
        $destroy = Room::where('RoomID',$id)->first();
        if($destroy != null){
            $check = Reservation::get();
            foreach($check as $Reservation){
                if($Reservation->room_id == $id){
                     $destroy2 = Reservation::where('room_id',$id)->delete();
                }
            }
            $destroy = Room::where('RoomID',$id)->delete();
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }
}
