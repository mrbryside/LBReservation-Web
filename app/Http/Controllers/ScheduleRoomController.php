<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Close;
use \Auth;

class ScheduleRoomController extends Controller
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
        $Closes = Close::orderBy('CloseStart')
                                ->where('CloseExam',0)
                                ->paginate(10);

        $Test = Close::where('CloseExam',1)->get();

        return view('scheduleroom.index')->with('admin',$admin)
        								 ->with('Closes',$Closes)
        								 ->with('Test',$Test);
    } 
    public function storeCloseTime(Request $request){

    	$Close = new Close;

    	$dateStart = $request->input('dateStart');
    	$dateStart_carbon = Carbon::parse($dateStart);
    	$dateStart_carbon = $dateStart_carbon->toDateString();

    	$dateEnd = $request->input('dateEnd');
        $dateEnd_carbon = Carbon::parse($dateEnd);
    	$dateEnd_carbon = $dateEnd_carbon->toDateString();

        $closeStart = $request->input('timestart');
        $closeStart_carbon = Carbon::parse($closeStart);
    	$closeStart_carbon = $closeStart_carbon->toTimeString();

        if($closeStart_carbon  == '00:00:00'){
            $closeStart_carbon ='00:00:01';
        }
        
        $closeEnd = $request->input('timeend');
        $closeEnd_carbon = Carbon::parse($closeEnd);
    	$closeEnd_carbon = $closeEnd_carbon->toTimeString();
    	if($closeEnd_carbon == '00:00:00'){
    		$closeEnd_carbon ='23:59:59';
    	}


    	if($dateStart_carbon > $dateEnd_carbon){
			\Session::flash('flash_message','วันที่เริ่มปิด ต้องน้อยกว่าหรือเท่ากับ วันที่สิ้นสุดการปิด');
    		return redirect()->back();
    	}
    	if($dateStart_carbon < Carbon::today()->toDateString()){
			\Session::flash('flash_message','วันที่เริ่มปิด ไม่สามารถเป็นวันในอดีต');
    		return redirect()->back();
    	}
    	if($dateEnd_carbon < Carbon::today()->toDateString()){
			\Session::flash('flash_message','วันที่สิ้นสุดการปิด ไม่สามารถเป็นวันในอดีต');
    		return redirect()->back();
    	}

    	if($dateStart_carbon == $dateEnd_carbon){
    		if($closeStart_carbon >= $closeEnd_carbon){
				\Session::flash('flash_message','เวลาเริ่มปิด ต้องน้อยกว่า เวลาสิ้นสุดการปิด');
	    		return redirect()->back();
	    	}
    	}
    	if($dateStart_carbon == Carbon::today()->toDateString()){
	    	if($closeStart_carbon < Carbon::now()->subMinute()->toTimeString()){
				\Session::flash('flash_message','เวลาเริ่มปิด ไม่สามารถเป็นเวลาในอดีต');
	    		return redirect()->back();
	    	}
	    }

    	if($dateEnd_carbon == Carbon::today()->toDateString()){
	    	if($closeEnd_carbon < Carbon::now()->subMinute()->toTimeString()){
	    		\Session::flash('flash_message','เวลาสิ้นสุดการปิด ไม่สามารถเป็นเวลาในอดีต');		
	    		return redirect()->back();
	    	}
	    }

	    $closechecks = Close::where('CloseExam',0)->get();
	    foreach($closechecks as $closecheck){
	    	$closecheck_start = Carbon::parse($closecheck->CloseStart);
	    	$closecheck_end = Carbon::parse($closecheck->CloseEnd);


	    	$closecheck_timeEnd = $closecheck_end->toTimeString();
	    	$closecheck_timestart = $closecheck_start->toTimeString();

	    	if($closecheck_timeEnd == '00:00:00'){
                $closecheck_timeEnd = '23:59:59';
            }
            if($closecheck_timestart == '00:00:00'){
                $closecheck_timestart = '00:00:10';
           	}

	    	$closecheck_dateEnd = $closecheck_end->toDateString();
	    	$closecheck_datestart = $closecheck_start->toDateString();


	    	if($closecheck_dateEnd == $dateEnd_carbon && $closecheck_datestart == $dateStart_carbon && $dateEnd_carbon == $dateStart_carbon){
	    		if($closeStart_carbon < $closecheck_timestart){
                    if($closeEnd_carbon > $closecheck_timestart){
                        \Session::flash('flash_message','เวลาปิดจองห้องของท่าน ซ้อนทับกับข้อมูลในระบบ');
                        return redirect()->back();
                    }
                }
                if($closeStart_carbon == $closecheck_timestart){
                    \Session::flash('flash_message','เวลาปิดจองห้องของท่าน ซ้อนทับกับข้อมูลในระบบ');
                    return redirect()->back();
                }
                if($closeStart_carbon > $closecheck_timestart){
                    if($closeStart_carbon < $closecheck_timeEnd){
                        \Session::flash('flash_message','เวลาปิดจองห้องของท่าน ซ้อนทับกับข้อมูลในระบบ');
                        return redirect()->back();
                     }
                }
	    	}
	    	if($dateEnd_carbon == $closecheck_dateEnd && $dateStart_carbon == $closecheck_datestart){
	    		if($closeStart_carbon == $closecheck_timestart && $closeEnd_carbon == $closecheck_timeEnd){
		    		\Session::flash('flash_message','เวลาปิดจองห้องของท่าน ซ้อนทับกับข้อมูลในระบบ');
	                return redirect()->back();
	            }
	    	}
	    }


        $closeStart = date('H:i', strtotime($closeStart));
        $closeStart .=':01';
        $dateStart = date('Y-m-d'.$closeStart, strtotime($dateStart));


        $closeEnd = date('H:i', strtotime($closeEnd));
        $closeEnd .=':00';
        $dateEnd = date('Y-m-d'.$closeEnd, strtotime($dateEnd));

        $store = new Close;
        $store->CloseStart = $dateStart;
        $store->CloseEnd = $dateEnd;
        $store->save();

        \Session::flash('flash_message2','เพิ่มเวลาปิดการจองสำเร็จ!');		
        return redirect()->back();

    }
    public function storeTestTime(){

    	$Close = Close::where('CloseExam',1)->first();
    	if($Close == null){
    		$store = new Close;
	    	$store->CloseExam = 1;
	    	$store->save();
    	}
    	return redirect()->back();
    }
    public function destroyCloseTime($id){
        $destroy = Close::where('CloseID',$id)->first();
        if($destroy != null){
            $destroy = Close::where('CloseID',$id)->delete();

            \Session::flash('flash_message2','ลบรายการสำเร็จ!');
            return redirect()->back();
        }
        return redirect()->back();    	
    }
    public function destroyTestTime(){
        $destroy = Close::where('CloseExam',1)->first();
        if($destroy != null){
            $destroy = Close::where('CloseExam',1)->delete();
        }
    	return redirect()->back();
    }

}
