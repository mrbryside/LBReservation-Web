<?php

namespace App\Http\Middleware;

use \Auth;
use Closure;

class IsWrite
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
         if (Auth::user()->StudentID != '-') {
                return $next($request);
         }

         return redirect()->to('/writeuser');
    }
}
