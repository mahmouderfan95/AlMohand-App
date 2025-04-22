<?php

namespace App\Http\Controllers\SalesRep\Auth;

use App\DTO\BaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesRep\Auth\LoginRequest;
use App\Http\Requests\SalesRep\Auth\RegisterRequest;
use App\Http\Resources\SalesRep\AuthResources\AuthResource;
use App\Interfaces\ServicesInterfaces\SalesRep\SalesRepAuthServiceInterface;
use App\Traits\ApiResponseAble;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
    use ApiResponseAble;
    public function __construct(private readonly SalesRepAuthServiceInterface $authService
    )
    {
    }

       /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="salesRepLogin",
     *     tags={"SalesRep Authentication"},
     *     summary="Login salesRep",
     *     description="Authenticate a Sales Rep using its username, password details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="123456789", description="The unique username of the Sales Rep."),
     *             @OA\Property(property="password", type="string", example="123456789", description="The password for the Sales Rep."),
     *           )
     *     ),

     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid username or password.")
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login(new BaseDTO($request));
    }

     /**
     * @OA\Post(
     *     path="/auth/register",
     *     operationId="salesRepRegister",
     *     tags={"SalesRep Authentication"},
     *     summary="Register a Sales Rep",
     *     description="Registers a new Sales Rep with the given details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="123456789", description="The unique username of the Sales Rep."),
     *             @OA\Property(property="password", type="string", format="password", example="123456789", description="The password for the Sales Rep."),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456789", description="Confirmation of the password."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Sales Rep registered successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...", description="JWT authentication token."),
     *                 @OA\Property(property="expires_in", type="integer", example=3600, description="Token expiration time in seconds.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return $this->authService->register(new BaseDTO($request));
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     operationId="getUserProfile",
     *     tags={"Profile"},
     *     summary="Retrieve user profile",
     *     description="Fetches the profile details of the currently authenticated user.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved user profile.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true,
     *                 description="Indicates if the request was successful."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getProfile()
    {
        return $this->ApiSuccessResponse(new AuthResource(auth('salesRepApi')->user()->load('children','sales_rep_level','sales_rep_locations')));
    }

    /**
     * @OA\Get(
     *     path="/auth/logout",
     *     operationId="logoutUser",
     *     tags={"SalesRep Authentication"},
     *     summary="Logout the user",
     *     description="Logs out the currently authenticated user by invalidating their session or token.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function logout()
    {
        return $this->authService->logout();
    }
}
