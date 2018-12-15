<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rule;
use App\RuleItem;

class ApiRuleController extends Controller
{
    public function ruleList(Request $request){
        $howtoArray = [];
        $ruleArray = [];
        $rules = Rule::get();
        foreach($rules as $rule){
            $ruleItems = RuleItem::where('rule_id',$rule->rule_id)->get();
            foreach($ruleItems as $ruleItem){
                if($rule->RuleType == 'howto'){
                    array_push($howtoArray,[
                        'text'=>$ruleItem->ruleText,
                    ]);
                }
                else{
                    array_push($ruleArray,[
                        'text'=>$ruleItem->ruleText,
                    ]);
                }      
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'ข้อมูล',
            'howto_data' => $howtoArray,
            'rule_data' => $ruleArray,
        ]);
    
        
    }
}
