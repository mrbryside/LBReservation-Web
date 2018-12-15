<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Validator;
use App\User;
use Carbon\Carbon;
use Auth;
use App\Reservationstore;
use App\Reservation;
use App\Room;

class ApiUserController extends Controller
{
    // public function userRegister(Request $request){
    // 	$validator = Validator::make($request->all(),[
    // 		'name' => 'required',
    // 		'email' => 'required|email|unique:users',
    // 		'password' => 'required',
    // 		'c_password' => 'required|same:password',

    // 	]);

    // 	if($validator->fails()){
    // 		return response()->json(['error' => $validator->errors()], 401);
    // 	}




    // 	$input = $request->all();
    // 	$input['password'] = bcrypt($input['password']);
    // 	$user = User::create($input);

    //     $tokenResult = $user->createToken('MyApp');
    //     $tokenResult->token->expires_at = Carbon::now()->addDays(1);
    //     $tokenResult->token->save();

    // 	$success['token'] = $tokenResult->accessToken;
    // 	$success['name'] = $user->name;
    // 	return response()->json(['success'=>$success],200);
    // }
    public function userLogin(Request $request){
        $validator = Validator::make($request->all(),[
            'StudentID' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        if(Auth::attempt(['StudentID' => request('StudentID') , 'password' => request('password')])){
            $user = Auth::user();
            $tokenResult = $user->createToken('MyApp');
            $tokenResult->token->expires_at = Carbon::now()->addDays(1);
            $tokenResult->token->save();
            
            $success['token'] = $tokenResult->accessToken;
    	    $success['FirstName'] = $user->Firstname;

            return response()->json(['success'=>$success],200);
        }
        else{
            return response()->json(['error' => 'Unuthorised'], 401);
        }
    }
    public function myInformation(Request $request,$userID){
        $user = User::where('id',$userID)->first();
        $allReservedHistory = Reservationstore::where('StudentID',$user->StudentID)->get();
        $countReserved = count($allReservedHistory);
        $countBan = $user->CountBan;

        $min = 0;
        foreach($allReservedHistory as $reserved){
            $start = Carbon::parse($reserved->ReservationStart);
            $end = Carbon::parse($reserved->ReservationEnd);
            $min += $start->diffInMinutes($end);
        }

        $hourUse = floor($min/60);

        $single = 0;
        $shared = 0;
        foreach($allReservedHistory as $reserved){
            $room = Room::where('RoomName',$reserved->RoomName)->first();
            if($room->RoomPeople == 1){
                $single++;
            }
            else{
                $shared++;
            }
        }


        $stat = [];
        array_push($stat,[
            'hourCount' => $hourUse,
            'singleCount' => $single,
            'sharedCount' => $shared,
            'banCount' => $countBan,
            'reservedCount' => $countReserved,
        ]);

        $info = [];
        array_push($info,[
            'firstName' => $user->Firstname,
            'lastName' => $user->Lastname,
            'email' => $user->email,
            'phone' => $user->Phone,
        ]);
        

        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'information' => $info,
            'statistic' => $stat
        ]);
        
    }
}
