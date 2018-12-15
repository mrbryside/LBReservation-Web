<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from google.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('email',$userSocial->email)->first();

        if($findUser){
            Auth::login($findUser);

            return redirect()->to('/userlogin');

        }
        else{
            if(strlen($userSocial->email) - strlen('@ku.th') != strrpos($userSocial->email,'@ku.th')){
                return redirect('https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='.url('/invalid'));
            }
            else{
                $user = new User;

                $split = explode(" ", $userSocial->name);

                $firstname = array_shift($split);
                $lastname  = implode(" ", $split);

                $firstname = strtolower($firstname);
                $firstname[0] = strtoupper($firstname);

                $lastname = strtolower($lastname);
                $lastname[0] = strtoupper($lastname);


                $user->Firstname = $firstname;
                $user->Lastname = $lastname;
                $user->Phone = '-';
                $user->StudentID = uniqid();
                $user->email = $userSocial->email;
                $user->password = bcrypt(substr(md5(uniqid(mt_rand(), true)), 0, 8));
                $user->save();

                Auth::login($user);

                return redirect()->to('/userlogin');
            }
            
        }
    }
    public function field()
    {
        if(filter_var(request()->StudentID,FILTER_VALIDATE_EMAIL)){
            return 'email';
        }
        else{
            return 'StudentID';
        }
    }
    public function login()
    {
        if(Auth::attempt([$this->field() => request()->StudentID , 'password' => request()->password])){
            return redirect()->intended('/userlogin');
        }
        else{
            \Session::flash('flash_message','คุณกรอก User ID หรือ รหัสผ่านผิด โปรดลองใหม่อีกครั้ง');
            return redirect()->back();
        }
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
