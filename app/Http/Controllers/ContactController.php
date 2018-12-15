<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use \Auth;

class ContactController extends Controller
{
    public function index(){
        $admin = Auth::user()->status;
        $contacts = Contact::get();
        return view('contact.index')->with('admin',$admin)
                                  ->with('contacts',$contacts);
    }
    public function create(){
        $admin = Auth::user()->status;
        $contacts = Contact::get();

        if(count($contacts) == 0){
        	return view('contact.create')->with('admin',$admin);
        }
        else{
        	return redirect()->back();
        }
        
    }
    public function edit($id){
        $admin = Auth::user()->status;
        $contact = Contact::find($id);
        if($contact == null){
            return redirect()->back();
        }
        return view('contact.edit')->with('admin',$admin)
        					       ->with('Contact',$contact);
    }
    public function store(Request $request){
    	$this->validate($request,[
                'WebName'=>'required|max:255',
                'Phone' => 'required|string|min:8|max:255',
        ]);

        $store = new Contact;
        $store->WebName = $request->input('WebName');
        $store->Phone = $request->input('Phone');
        $store->save();

        \Session::flash('flash_message','สร้างข้อมูลการติดต่อสำเร็จ!');
        return redirect()->to('/contact');
    }
    public function update($id ,Request $request){
    	$this->validate($request,[
                'WebName'=>'required|max:255',
                'Phone'=>'required|string|min:8|max:255',
        ]);
        $update = Contact::find($id);
        if($update == null){
            return redirect()->back();
        }
        $update->WebName = $request->input('WebName');
        $update->Phone = $request->input('Phone');
        $update->save();

        \Session::flash('flash_message','แก้ไขข้อมูลการติดต่อสำเร็จ!');
        return redirect()->to('/contact');
    }
}
