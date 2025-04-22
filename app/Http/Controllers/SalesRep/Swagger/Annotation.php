<?php

namespace App\Http\Controllers\SalesRep\Swagger;

use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 * title="APIs For Al-Mohannad POS",
 * version="1.0.0",
 * ),
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_SALESREP_HOST,
 *     description="SalesRep API Server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 * @OA\Response(
 *      response="Unauthenticated",
 *      description="Unauthenticated response",
 *      @OA\JsonContent(
 *          @OA\Property(property="status", type="boolean", example=false),
 *          @OA\Property(property="code", type="integer", example=401),
 *          @OA\Property(property="message", type="string", example="Unauthenticated."),
 *          @OA\Property(property="errors", type="string", nullable=true, example=null)
 *      )
 *  )
 */
class Annotation
{}
