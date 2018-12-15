<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newmodel;
use \Auth;

class LoginRedirectController extends Controller
{
    public function invalidlogin(){
        \Session::flash('flash_message3','e-mail ต้องเป็น @ku.th เท่านั้น');
        return redirect()->to('/login');

    }
    public function logoutGoogle(){
        \Session::flash('flash_message2','คุณได้ Logout จากบัญชี Google เรียบร้อยแล้ว');
        return redirect()->to('/login');

    }
}