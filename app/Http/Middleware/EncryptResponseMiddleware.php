<?php

namespace App\Http\Middleware;

use App\Helpers\OpenSslHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;

class EncryptResponseMiddleware
{

    protected OpenSslHelper $opensslHelper;

    public function __construct(OpenSslHelper $opensslHelper)
    {
        $this->opensslHelper = $opensslHelper;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws RandomException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
            $content = $response->getContent();
            $encryptedContent = $this->opensslHelper->encrypt(json_decode($content, true))->getContent();
            $response->setContent($encryptedContent);
            $response->header('Content-Type', 'text/plain');
            return $response;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Response::HTTP_BAD_REQUEST;
        }
    }
}
