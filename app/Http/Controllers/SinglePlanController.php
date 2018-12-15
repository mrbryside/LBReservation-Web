<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use App\Plan;
use \Auth;

class SinglePlanController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
                'ImageName'=>'required|mimes:jpeg,jpg,png|max:4096',
        ]);

        // if(count($errors) > 0) {
        //     \Session::flash('flash_message','ดำเนินการไม่สำเร็จ ไฟล์ภาพต้องเป็น jpeg,jpg,png เท่านั้น');
        //     return redirect()->to('/single');
        // }

        $store = new Plan;
        if(Input::hasFile('ImageName')){
            $im=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/seimg/';
            $image->resize(700,467);
            $image->save($path.$im->getClientOriginalName());

        }
        $store->ImageName = $im->getClientOriginalName();
        $store->ImageSize = $im->getClientsize();
        $store->ImageType = $im->getClientMimeType();
        $store->save();

        \Session::flash('flash_message2','อัพเดทแผนผังห้องศึกษาเดี่ยวสำเร็จ!');
        return redirect()->to('/single');
        
    }
    public function update(Request $request,$id){
        $this->validate($request,[
                'ImageName'=>'required|mimes:jpeg,jpg,png|max:4096',
        ]);

        $update = Plan::find($id);
        if($update == null){
             return redirect()->to('/single');
        }
        if(Input::hasFile('ImageName')){
            $im=Input::File('ImageName');
            $image = Image::make(Input::file('ImageName'));
            $path = public_path().'/seimg/';
            $image->resize(700,467);
            $image->save($path.$im->getClientOriginalName());

        }
        $update->ImageName = $im->getClientOriginalName();
        $update->ImageSize = $im->getClientsize();
        $update->ImageType = $im->getClientMimeType();
        $update->save();

        \Session::flash('flash_message2','อัพเดทแผนผังห้องศึกษาเดี่ยวสำเร็จ!');
        return redirect()->to('/single');
        
    }
}
