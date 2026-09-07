<?php

namespace App\Http\Middleware;

use Closure;

class AdminVerification
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
        if ((\Auth::user()->licence == "3" || \Auth::user()->licence == "2" || \Auth::user()->licence == "4") && \Auth::user()->permission == 1)
        {
            return $next($request);
        }
        elseif(\Auth::user()->applock == 0){
            \Auth::logout();
            \Session::flush();
            return redirect()->back()->with('warning',"Your Account has been blocked");
        }
        return redirect()->back();
    }
}
