<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Student;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $student = Student::where('email', Auth::user()->email)->first();
            if(Auth::user()->admin === 1)
                return redirect('/student/');
            else
                return redirect('/student/' . $student->first_name . '.' .  $student->last_name);
        }

        return $next($request);
    }
}
