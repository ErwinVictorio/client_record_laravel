<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SalesManMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // chech if the user is not login
        if (!Auth::check()) {
            // then mag flash tau ng error
            session()->flash('error','please login your account');
            return redirect()->route('login.view');
        }

        // check if  the role is correcrt
         if (Auth::user()->role != 3) {
            session()->flash('error','Access Denied!');
            return redirect()->route('login.view');
         }

        return $next($request);
    }
}
