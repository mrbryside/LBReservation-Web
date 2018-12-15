<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newmodel;

class ApiNewController extends Controller
{
    public function newList(Request $request){
        $res = [];
        $news = Newmodel::get();
        foreach($news as $new){
            array_push($res,[
                'new_id'=>$new->Newid,
                'new_name'=>$new->NewTitle,
                'new_des'=>$new->NewDescription,
                'new_createdAt'=>$new->created_at->format('d-m-y'),
                'image_url'=>env('APP_URL','').'/thumbnails3'.'/'.$new->ImageName,
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'data' => $res
        ]);
    
        
    }
    public function newDetail(Request $request,$newID){
        $res = [];
        $new = Newmodel::where('Newid',$newID)->first();

        array_push($res,[
            'new_name'=>$new->NewTitle,
            'new_paragraph'=>$new->NewParagraph1,
            'image_url'=>env('APP_URL','').'/thumbnails3'.'/'.$new->ImageName,
        ]);

    
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'data' => $res
        ]);
    
        
    }
}
