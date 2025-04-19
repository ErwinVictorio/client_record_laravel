<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CashierMidllewire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // check if the user is naka login pag false redirect to login page
        if (!Auth::check()) {
            session()->flash('error','You need to login to proceed');
            return redirect()->route('login.view');
        }

        if (Auth::user()->role != 2) {
            session()->flash('error','Access Denied');

            return redirect()->route('login.view');
        }



        return $next($request);
    }
}
