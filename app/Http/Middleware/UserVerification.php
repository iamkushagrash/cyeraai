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
        if (!\Auth::check()) {
            return redirect('/login');
        }

        if (\Auth::user()->permission == 0) {
            \Auth::logout();
            \Session::flush();
            return redirect('/login')->with('warning', "Your Account has been blocked");
        }

        if (\Auth::user()->licence == "3" || \Auth::user()->licence == "2" || \Auth::user()->licence == "4") {
            return redirect('/Main/DashboardToday');
        }

        if (\Auth::user()->permission == 1) {
            return $next($request);
        }

        return redirect('/login');
    }
}
