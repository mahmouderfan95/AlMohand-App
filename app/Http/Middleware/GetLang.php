<?php

namespace App\Http\Middleware;

use Closure;

class GetLang
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
        app()->setLocale('ar');
        if($request->hasHeader('lang')){
            app()->setLocale($request->header('lang'));
        }
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
