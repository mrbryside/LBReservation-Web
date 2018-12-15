<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'Firstname' => 'required|string|max:255',
            'Lastname' => 'required|string|max:255',
            'Phone' => 'required|string|min:9|max:10',
            'Faculty' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'StudentID' => 'required|numeric|digits:10|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'Firstname' => $data['Firstname'],
            'Lastname' => $data['Lastname'],
            'Phone' => $data['Phone'],
            'Faculty' => Input::get('Faculty'),
            'email' => $data['email'],
            'StudentID' => $data['StudentID'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
