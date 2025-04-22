<?php

namespace App\Http\Middleware;

use App\Helpers\OpenSslHelper;
use App\Traits\ApiResponseAble;
use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NotFoundWhenProduction
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $env = config('app.env');
        if($env == "production"){
            $this->unauthorized();
        }
        return $next($request);
    }

    private function unauthorized(){
        abort(403, 'Forbidden');
    }
}
