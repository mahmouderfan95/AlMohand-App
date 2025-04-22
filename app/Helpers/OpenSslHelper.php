<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Random\RandomException;

class OpenSslHelper
{
    /**
     * @var string
     */
    private string $iv;
    /**
     * @var string
     */
    private string $secret;
    /**
     * @var string
     */
    private string $cipher;
    /**
     * @throws RandomException
     */

    public function __construct()
    {
        $this->cipher = "aes-256-cbc";
        $this->secret = "wKJPC60aEYmNgTrPyxACEI316HzT88RyVxLfxGsedZViGFndZAowJx50rKIiDX9j";
    }

    /**
     * @param $data
     * @return \Illuminate\Http\Response
     * @throws RandomException
     */
    public function encrypt($data): \Illuminate\Http\Response
    {
        $object = json_encode($data);
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($object, $this->cipher, $this->secret, OPENSSL_RAW_DATA, $iv);
        $result = base64_encode($iv . $encrypted);
        return Response::make($result)->header('Content-Type', 'text/plain');
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function decrypt(Request $request): mixed
    {
        $encryptedData = base64_decode($request->getContent());
        $iv = substr($encryptedData, 0, 16);
        $encryptedPayload = substr($encryptedData, 16);
        $decrypted = openssl_decrypt($encryptedPayload, $this->cipher, $this->secret, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            return response()->json(['error' => 'Decryption failed'], 400);
        }
        return json_decode($decrypted, true);
    }
}
