<?php
namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'loginAdmin']]);
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'Some Error Message'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }

        return $this->respondWithToken($token, auth()->guard('api')->user());
    }

    protected function respondWithToken($token, $user)
    {
        $user->load('roles','permissions');
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ]);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }


    public function profile()
    {
        try {
            $user = Auth::user();
            if ($user)
                return response()->json(['status' => 'success', 'message' => 'User Profile', 'data' => $user]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something wrong Please Try Again',
            ], 400);
        }
    }


}
