<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

      // checking if hindi naka login ang user 
      if (!Auth::check()) {
        session()->flash('error','You need to login to proceed');
        
         return redirect()->route('login.view');
      }

    // check if the user na nag login ay hindi admin 
      if (Auth::user()->role != 1) {
        session()->flash('error','Access Denied');   

       return redirect()->route('login.view');
      }

        return $next($request);
    }
}
    