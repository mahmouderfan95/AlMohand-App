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

class DecryptRequestMiddleware
{

    use ApiResponseAble;
    protected OpenSslHelper $opensslHelper;

    public function __construct(OpenSslHelper $opensslHelper)
    {
        $this->opensslHelper = $opensslHelper;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($request->method() === 'GET') {
                return $next($request);
            }

            $content = $request->getContent();
            if (empty($content) || !is_string($content)) {
                return $this->ApiErrorResponse("Invalid Data Format.");
            }

            $decryptedData = $this->opensslHelper->decrypt($request);

            if (! is_array($decryptedData)) {
                $response = $this->ApiErrorResponse("Invalid Body Format.");
                return $this->encryptErrorResponse($response);
            }

            $request->merge($decryptedData);

            return $next($request);

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->ApiErrorResponse("Invalid Data Format");
        }
    }

    private function encryptErrorResponse($response)
    {
        $content = $response->getContent();
        $encryptedContent = $this->opensslHelper->encrypt(json_decode($content, true))->getContent();
        $response->setContent($encryptedContent);
        $response->header('Content-Type', 'text/plain');
        return $response;
    }
}
