<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // check if the user is not login
        if (!Auth::check()) {
            session()->flash('error', 'You Need to login to proceed');
            
            return redirect()->route('login.view');
        }



        // next check the role of the user if not === 0 then this is not a super admin

        if (Auth::user()->role != 0) {
                session()->flash('error', 'Access Denied');
            
            return redirect()->route('login.view');
        }

        return $next($request);
    }
}
