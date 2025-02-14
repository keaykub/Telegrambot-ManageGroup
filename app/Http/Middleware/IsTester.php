<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsTester
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->ADMIN_ROLE == 'TESTER') {
            return $next($request);
        }

        return redirect('/');
    }
}
