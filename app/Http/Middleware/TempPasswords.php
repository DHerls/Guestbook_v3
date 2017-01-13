<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Http\Request;

class TempPasswords
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (\Auth::user()->temp_pass && $request->route()->getPath() != 'set-pass'){
            return redirect('/set-pass');
        }

        return $next($request);
    }
}
