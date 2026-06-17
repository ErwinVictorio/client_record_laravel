<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AfterSalesMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login your account');
            return redirect()->route('login.view');
        }

        if (!in_array((int) Auth::user()->role, [0, 4], true)) {
            session()->flash('error', 'Access Denied!');
            return redirect()->route('login.view');
        }

        return $next($request);
    }
}
