<?php

namespace App\Http\Middleware;

use \Auth;
use Closure;

class IsAdminStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if(Auth::guest()){
            return redirect()->to('/login');
         }
         if (Auth::user() &&  Auth::user()->status == 2 or Auth::user() &&  Auth::user()->status == 1) {
                return $next($request);
         }

         return redirect()->back();
    }
}
