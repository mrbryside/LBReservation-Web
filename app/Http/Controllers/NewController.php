<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Newmodel;
use App\Contact;
use Illuminate\Support\Facades\Input;
use Image;
class NewController extends Controller
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
        $New = Newmodel::get();
        $contacts = Contact::get();
        return view('new.new')->with('admin',$admin)
                                ->with('New',$New)
                                ->with('contacts',$contacts);
    }
    public function create(){
        $admin = Auth::user()->status;
        return view('new.create')->with('admin',$admin);
    }

    public function show($id){

        $New = Newmodel::find($id);
        if($New == null){
            return redirect()->back();
        }
        $admin = Auth::user()->status;
        return view('new.show')->with('New',$New)
                               ->with('admin',$admin);
    }

    public function store(Request $request){
        $this->validate($request,[
                'NewTitle'=>'required|string|max:180',
                'NewDescription'=>'required|string|max:180',
                'ImageName'=>'required|mimes:jpeg,jpg,png|max:4096',
        ]);

        $store = new Newmodel;
        $store->NewTitle = $request->input('NewTitle');
        $store->NewDescription = $request->input('NewDescription');
        $store->NewParagraph1 = $request->input('NewParagraph1');
        if(Input::hasFile('ImageName')){
            $New=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/thumbnails2/';
            $path2 = public_path().'/thumbnails3/';

            $image->resize(500,460);
            $image->save($path.$New->getClientOriginalName());
            $image->resize(500,385);
            $image->save($path2.$New->getClientOriginalName());

            $store->ImageName = $New->getClientOriginalName();
            $store->ImageSize = $New->getClientsize();
            $store->ImageType = $New->getClientMimeType();

        }

        $store->save();

        return redirect()->to('/new');
    }
    public function edit($id){
        $New = Newmodel::find($id);
        if($New == null){
            return redirect()->back();
        }
        $admin = Auth::user()->status;
        return view('new.edit')->with('New',$New)
                               ->with('admin',$admin);
    }

    public function update($id, Request $request){
        $this->validate($request,[
                'NewTitle'=>'required|string|max:180',
                'NewDescription'=>'required|string|max:180',
                'ImageName'=>'mimes:jpeg,jpg,png|max:4096',
        ]);

        $update = Newmodel::find($id);
        if($update == null){
            return redirect()->back();
        }
        $update->NewTitle = $request->input('NewTitle');
        $update->NewDescription = $request->input('NewDescription');
        $update->NewParagraph1 = $request->input('NewParagraph1');
        if(Input::hasFile('ImageName')){
            $New=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/thumbnails2/';
            $path2 = public_path().'/thumbnails3/';

            $image->resize(500,460);
            $image->save($path.$New->getClientOriginalName());
            $image->resize(500,385);
            $image->save($path2.$New->getClientOriginalName());

            $update->ImageName = $New->getClientOriginalName();
            $update->ImageSize = $New->getClientsize();
            $update->ImageType = $New->getClientMimeType();

        }
        
        $update->save();

        return redirect()->to('/new');
    }

    public function destroy($id){
        $destroy = Newmodel::where('NewID',$id)->first();
        if($destroy != null){
            $destroy = Newmodel::where('NewID',$id)->delete();
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
        
    }
}
