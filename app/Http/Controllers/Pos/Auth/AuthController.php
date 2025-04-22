<?php

namespace App\Http\Controllers\Pos\Auth;

use App\DTO\Pos\Auth\FactoryResetDto;
use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\Auth\PosRegisterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Auth\FactoryResetRequest;
use App\Http\Requests\Pos\Auth\LoginRequest;
use App\Http\Requests\Pos\Auth\RegisterRequest;
use App\Http\Resources\Pos\AuthResources\AuthResource;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosAuthServiceInterface;
use App\Services\Distributor\DistributorPosTerminalService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
    use ApiResponseAble;
    public function __construct(private readonly PosAuthServiceInterface $authService,
                                private readonly DistributorPosTerminalService $distributorPosTerminalService
    )
    {
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="posLogin",
     *     tags={"POS Authentication"},
     *     summary="Login POS Terminal",
     *     description="Authenticate a POS terminal using its serial number, password, and additional details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"serial_number", "password", "app_version", "location"},
     *             @OA\Property(property="serial_number", type="string", example="123456789", description="The unique serial number of the POS terminal."),
     *             @OA\Property(property="password", type="string", example="123456789", description="The password for the POS terminal."),
     *             @OA\Property(property="app_version", type="string", example="1.2.0", description="The version of the POS application."),
     *             @OA\Property(property="location", type="string", example="31.254523233,30.54562222125", description="The GPS location of the POS terminal (latitude,longitude).")
     *         )
     *     ),

     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid serial number or password.")
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login(new PosLoginDto($request));
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     operationId="posRegister",
     *     tags={"POS Authentication"},
     *     summary="Register a POS Terminal",
     *     description="Registers a new POS Terminal with the given details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"serial_number", "password", "password_confirmation", "otp", "app_version", "location"},
     *             @OA\Property(property="serial_number", type="string", example="123456789", description="The unique serial number of the POS terminal."),
     *             @OA\Property(property="password", type="string", format="password", example="123456789", description="The password for the POS terminal."),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456789", description="Confirmation of the password."),
     *             @OA\Property(property="otp", type="string", example="1111", description="The one-time password (OTP) for verification."),
     *             @OA\Property(property="app_version", type="string", example="1.2.0", description="The version of the POS application."),
     *             @OA\Property(property="location", type="string", example="31.254523233,30.54562222125", description="The GPS location of the POS terminal (latitude,longitude).")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="POS Terminal registered successfully."),
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
        return $this->authService->register(new PosRegisterDto($request));
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
        return $this->ApiSuccessResponse(new AuthResource(auth('posApi')->user()));
    }

    /**
     * @OA\Post(
     *     path="/profile/update-phone",
     *     operationId="updatePhoneNumber",
     *     tags={"Profile"},
     *     summary="Update user phone number",
     *     description="Allows a user to update their phone number.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"phone"},
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example="01100552222",
     *                 description="The new phone number to update."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updatePhone(Request $request)
    {
        $validator = Validator::make($request->all(), ['phone' => [
            'required',
            'string',
            'regex:/^(\+?[0-9]{1,3})?([0-9]{10,15})$/',
        ]]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(['phone' => 'Invalid Phone']);
        }
        return $this->authService->updatePhone($request->phone);
    }

    /**
     * @OA\Post(
     *     path="/profile/update-name",
     *     operationId="updateUserName",
     *     tags={"Profile"},
     *     summary="Update user name",
     *     description="Allows a user to update their name.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Sayed Hanss",
     *                 description="The new name to update."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required|max:50|min:2']);
        return $this->authService->updateName($request->name);
    }

    /**
     * @OA\Post(
     *     path="/profile/update-password",
     *     operationId="updatePassword",
     *     tags={"Profile"},
     *     summary="Update POS user password",
     *     description="Allows a POS user to update their password by providing the old password and a new password with confirmation.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"old_password", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="old_password",
     *                 type="string",
     *                 example="123123123",
     *                 description="The current password of the user."
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="123123123",
     *                 description="The new password to be set."
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 example="123123123",
     *                 description="The confirmation of the new password."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        return $this->authService->updatePassword($request->old_password, $request->password);
    }

    /**
     * @OA\Get(
     *     path="/auth/logout",
     *     operationId="logoutUser",
     *     tags={"POS Authentication"},
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

    /**
     * @OA\Post(
     *     path="/factory-reset",
     *     operationId="factoryReset",
     *     tags={"System"},
     *     summary="Perform a factory reset",
     *     description="Resets the system to its factory settings after verifying the OTP.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"otp"},
     *             @OA\Property(
     *                 property="otp",
     *                 type="string",
     *                 example="1111",
     *                 description="The one-time password (OTP) required to confirm the reset."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function factoryReset(FactoryResetRequest $request)
    {
        $pos_terminal_id = auth('posApi')->user()->pos_terminal_id;
        return $this->distributorPosTerminalService->factoryReset($pos_terminal_id, (new FactoryResetDto($request))->getOtp());
    }
}
