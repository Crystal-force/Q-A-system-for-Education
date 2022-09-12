<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminCheck
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
        $url = parse_url($request->url())['path'];
        $subPath = substr($url, 1, 5);

        // If normal user try to access admin url
        if (Auth::user()->role == 0 && $subPath == "admin") return redirect('select-category');
        
        return $next($request);
    }
}
