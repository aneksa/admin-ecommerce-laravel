<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthAdmin
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
        // throw new \Exception(Auth::guard('admin')->user());
        if(Auth::guard('admin')->check()) {
            return $next($request);
        } else {
            session()->flash('message', 'Anda belum login');
            session()->flash('title', 'Error');
            session()->flash('icon', 'error');
            return redirect('/backend/login');
        }
    }
}
