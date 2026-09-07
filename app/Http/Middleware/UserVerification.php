<?php

namespace App\Http\Middleware;

use Closure;

class UserVerification
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
        if (\Auth::user()->licence == "1" && \Auth::user()->permission == 1)
        {
            return $next($request);
        }
        elseif(\Auth::user()->permission == 0){
            \Auth::logout();
            \Session::flush();
            return redirect()->back()->with('warning',"Your Account has been blocked");
        }
        return redirect()->back();
    }
}
