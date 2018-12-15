<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Rule;
use App\RuleItem;
use \Auth;


class RuleController extends Controller
{
   public function index(){
        $admin = Auth::user()->status;
        $rules = Rule::get();
        $ruleItem = RuleItem::get();
        return view('rule.index')->with('admin',$admin)
                                  ->with('rules',$rules)
                                  ->with('ruleItems',$ruleItem);
    }
    public function create(){
        $admin = Auth::user()->status;
        $howtoCount = 0;
        $ruleCount = 0;
        $rules = Rule::get();   
        foreach($rules as $rule){
        	if($rule->RuleType == 'howto'){
        		$howtoCount++;
        	}
        	else{
        		$ruleCount++;
        	}
        }
        if(($howtoCount < 1 || $ruleCount < 1)){
        	return view('rule.create')->with('admin',$admin);
        }
        else{
            \Session::flash('flash_message2','ไม่สามารถเพิ่มได้เกิน 2 หัวข้อใหญ่!');
        	return redirect()->back();
        }
        
    }
    public function edit($id){
        $admin = Auth::user()->status;
        $rule = Rule::find($id);
        $ruleItemFirst = RuleItem::where('rule_id',$id)->first();
        $ruleItems = RuleItem::where('rule_id',$id)->where('ruleItem_id','!=',$ruleItemFirst->ruleItem_id)->get();

        if($rule == null){
            return redirect()->back();
        }
        return view('rule.edit')->with('admin',$admin)
        					       ->with('rule',$rule)
                                   ->with('ruleItems',$ruleItems)
                                   ->with('ruleItemFirst',$ruleItemFirst);
    }
    public function store(Request $request){
    	$this->validate($request,[
                'ruleType'=>'required',
                'ruleTitle'=>'required|max:255',
        ]);
    	$rules = Rule::get();
    	$howtoCount = 0;
    	$ruleCount = 0;

    	foreach($rules as $rule){
        	if($rule->RuleType == 'howto'){
        		$howtoCount++;
        	}
        	else{
        		$ruleCount++;
        	}
        }

        if($howtoCount == 1 && $request->input('ruleType') == 'howto'){
            \Session::flash('flash_message2','ไม่สามารถเพิ่มวิธีใช้ ซ้ำได้อีก!');
        	return redirect()->back();
        }

        if($ruleCount == 1 && $request->input('ruleType') == 'rule'){
            \Session::flash('flash_message2','ไม่สามารถเพิ่มข้อปฏิบัติ ซ้ำได้อีก!');
        	return redirect()->back();
        }

        $ruleStore = new Rule;
        $ruleStore->RuleType = Input::get('ruleType');
        $ruleStore->RuleTitle = $request->input('ruleTitle');
        $ruleStore->save();
        $i = 0;
        foreach($request->all() as $req){

            if($i > 2){
                $ruleItem = new RuleItem;
                $ruleItem->ruleText = $req;
                $ruleItem->rule_id = $ruleStore->rule_id;
                $ruleItem->save();

            }
            $i++;
        } 
        
        \Session::flash('flash_message','สร้างข้อมูลสำเร็จ!');
        return redirect()->to('/rule');
    }
    public function update($id ,Request $request){
    	$this->validate($request,[
                'ruleType'=>'required',
                'ruleTitle'=>'required|max:255',
        ]);
        $rules = Rule::get();
        $howtoCount = 0;
        $ruleCount = 0;

        foreach($rules as $rule){
            if($rule->RuleType == 'howto'){
                $howtoCount++;
            }
            else{
                $ruleCount++;
            }
        }

        $ruleStore = Rule::find($id);
        $ruleStore->RuleType = Input::get('ruleType');
        $ruleStore->RuleTitle = $request->input('ruleTitle');
        $ruleStore->save();

        RuleItem::where('rule_id',$id)->delete();
        $i = 0;
        foreach($request->all() as $req){

            if($i > 3){
                $ruleItem = new RuleItem;
                $ruleItem->ruleText = $req;
                $ruleItem->rule_id = $ruleStore->rule_id;
                $ruleItem->save();

            }
            $i++;
        } 
        

        \Session::flash('flash_message','แก้ไขข้อมูลสำเร็จ!');
        return redirect()->to('/rule');
    }
    public function destroy($id){
    	Rule::find($id)->delete();
    	RuleItem::where('rule_id',$id)->delete();

        \Session::flash('flash_message2','ลบข้อมูลสำเร็จ!');
    	return redirect()->back();

    }
}
