<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use App\Reservationstore;
use App\Reservation;
use App\Usermodel;
use App\Ban;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use \Auth;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	public function index(Request $request){
        $s = '';
        $admin = Auth::user()->status;
        $Users = User::latest()
                ->where('status',0)
                ->paginate(15);

        return view('user.user')->with('admin',$admin)
                                ->with('Users',$Users)
                                ->with('s',$s);
    }
    public function indexSearch(Request $request){
        $s = $request->input('s');
        if($request->input('s') == null){
            $s = '';
        }
        $admin = Auth::user()->status;
        $Users = User::latest()
                ->search($s)
                ->where('status',0)
                ->paginate(15);

        return view('user.user')->with('admin',$admin)
                                ->with('Users',$Users)
                                ->with('s',$s);
    }
    public function staffindex(Request $request){
        $s = '';
        $admin = Auth::user()->status;
        $Users = User::orderBy('status')
                ->Where('status',2)
                ->paginate(15);

        return view('user.staff',compact('Users','s'))->with('admin',$admin);
    }
    public function staffindexSearch(Request $request){
        $s = $request->input('s');
        if($request->input('s') == null){
            $s = "";
        }
        $admin = Auth::user()->status;
        $Users = User::orderBy('status')
                ->search($s)
                ->Where('status',2)
                ->paginate(15);

        return view('user.staff',compact('Users','s'))->with('admin',$admin);
    }
    public function show($id){

        $Users = User::find($id);
        if($Users == null){
            return redirect()->back();
        }
        if(Auth::user()->status == 2 && $Users->id != Auth::user()->id && $Users->status == 2){
            return redirect()->back();
        }
        if(Auth::user()->status == 0 && $Users->id != Auth::user()->id){
            return redirect()->back();
        }
        $admin = Auth::user()->status;
        $information = 1;
        return view('user.manage')->with('Users',$Users)
                                ->with('admin',$admin)
                                ->with('information',$information);
    }
    public function changepassword(Request $request,$id){
        
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->to('/error404');
        }
        $admin = Auth::user()->status;
        $staff = -1;
        if($staff != Auth::user()->id){
            $staff = User::find($id);
        }      
        if(Auth::user()->id == $id || Auth::user()->status == 1){
            if(Auth::user()->id == 1 && Auth::user()->id != $id ){
                return view('user.changepassword')->with('admin',$admin)
                                              ->with('staff',$staff);
            }
            else{
                return view('user.changepassword')->with('admin',$admin)
                                              ->with('staff',$staff);
            }

        }
        else{
            return redirect()->back();
        }
        
    }
    public function changeinfomation(Request $request,$id){

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->to('/error404');
        }
        $admin = Auth::user()->status;
        $staff = -1;
        if($staff != Auth::user()->id){
            $staff = User::find($id);
        }      
        if(Auth::user()->id == $id || Auth::user()->status == 1){
            if(Auth::user()->id == 1 && Auth::user()->id != $id ){
                return view('user.edit')->with('admin',$admin)
                                        ->with('staff',$staff);
            }
            else{
                return view('user.edit')->with('admin',$admin)
                                        ->with('staff',$staff);
            }

        }
        else{
            return redirect()->back();
        }
    }
    public function updatepassword(Request $request,$id){
        $idbefore = $id;
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->to('/error404');
        }
        $this->validate($request,[
                'password' => 'required|string|min:6|max:255|confirmed',
        ]);  
        $update = User::find($id);
        if($update == null){
            return redirect()->back();
        }
        if(password_verify($request->input('oldpassword'),$update->password) ){
            $update->password = Hash::make($request->input('password'));
            $update->save();
            \Session::flash('flash_message','เปลี่ยนพาสเวิร์ดสำเร็จ!');
            if($id == auth::user()->id){
                return redirect()->to('/user/manage/'.$idbefore);
            }
            else{
                return redirect()->to('/user/'.$id);
            }
        }   
        else{
            if(Auth::user()->id == $id ){
                \Session::flash('flash_message','พาสเวิร์ดเก่าของคุณไม่ถูกต้อง');
                return redirect()->back();
            }
            else{
                $update->password = Hash::make($request->input('password'));
                $update->save();
                \Session::flash('flash_message','เปลี่ยนพาสเวิร์ดสำเร็จ!');
                if($id == auth::user()->id){
                    return redirect()->to('/user/manage/'.$idbefore);
                }
                else{
                    return redirect()->to('/user/'.$id);
                }
            }
        }

    }
    public function staffForm(){
        $admin = Auth::user()->status;
        return view('user.staffregister')->with('admin',$admin);
    }
    public function staff(Request $request){
        $this->validate($request,[
                'StudentID' => 'required|string|unique:users',
                'password' => 'required|string|min:6|max:255|confirmed',
        ]);
        $staff = new Usermodel;
        $staff->StudentID = $request->input('StudentID');
        $staff->Firstname = 'Library';
        $staff->Lastname = 'Staff';
        $staff->Phone = '0990000000';
        $staff->email = $request->input('StudentID').'@email.com';
        $staff->status = 2;
        $staff->password = Hash::make($request->input('password'));
        $staff->save();

        \Session::flash('flash_message','สร้างสตาฟสำเร็จ!');
        return redirect()->to('/user/show/staff');
    }
    public function updateinfomation(Request $request,$id){
        $idbefore = $id;
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->to('/error404');
        }
        $update = User::find($id);
        if($update == null){
            return redirect()->back();
        }
        if(Auth::user()->status == 0){
             $this->validate($request,[
                'StudentID' => 'required|numeric|digits:10|unique:users,StudentID,'.$id,
                'Firstname' => 'required|string|max:255',
                'Lastname' => 'required|string|max:255',
                'Phone' => 'required|string|min:9|max:10',
                'email' => [
                    'required',
                    'string',
                    'max:255',
                    'email',
                    Rule::unique('users')->ignore($update->id, 'id')
                ],
            ]);
        }
        else{
            $this->validate($request,[
                'StudentID' => 'required|string',
                'Firstname' => 'required|string|max:255',
                'Lastname' => 'required|string|max:255',
                'Phone' => 'required|string|min:9|max:10',
                'email' => [
                    'required',
                    'string',
                    'max:255',
                    'email',
                    Rule::unique('users')->ignore($update->id, 'id')
                ],
            ]);
        }
       
        $reservationstores = Reservationstore::get();
        $reservationstoreid = 0;
        foreach($reservationstores as $reservationstore){
            if($reservationstore->StudentID == auth()->user()->StudentID || $reservationstore->StudentID == $request->input('StudentIDOld')){
                $reservationstore->StudentID = $request->input('StudentID');
                $reservationstore->FirstName = $request->input('Firstname');
                $reservationstore->LastName = $request->input('Lastname');   
                $reservationstore->Faculty = Input::get('Faculty');
                $reservationstore->save();
            }
        }
        $update->Firstname = $request->input('Firstname');
        $update->Lastname = $request->input('Lastname');
        $update->StudentID = $request->input('StudentID');
        $update->Phone = $request->input('Phone');
        $update->email = $request->input('email');
        $update->Faculty = Input::get('Faculty');
        $update->save();

        


        \Session::flash('flash_message','เปลี่ยนข้อมูล profile สำเร็จ!');
        if($id == auth::user()->id){
            return redirect()->to('/user/manage/'.$idbefore);
        }
        else{
            return redirect()->to('/user/'.$id);
        }
        
    }
    public function manage($id){
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->to('/error404');
        }
        $admin = Auth::user()->status;
        $Users = User::find($id);
        $information = 0;
        if(Auth::user()->id == $id){
            return view('user.manage')->with('admin',$admin)
                                  ->with('information',$information)
                                  ->with('Users',$Users);
        }
        else{
            return redirect()->back();
        }
        
    }
    public function destroy($id){
        $destroy = User::where('id',$id)->first();
        if($destroy != null){
            Reservation::where('user_id',$id)->delete();
            Ban::where('user_id',$id)->delete();
            $destroy = User::where('id',$id)->delete();         
        }
        \Session::flash('flash_message','ลบข้อมูลสำเร็จ!');
        return redirect()->back();
        
    }
 }
