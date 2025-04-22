<?php
namespace App\Services\Seller;

use App\Helpers\FileUpload;
use App\Http\Resources\Seller\AuthResource;
use App\Http\Resources\Seller\SellerResource;
use App\Models\Seller\Seller;
use App\Repositories\Seller\AuthRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class AuthService{
    use ApiResponseAble,FileUpload;
    public function __construct(public AuthRepository $authRepository){}
    public function register($request)
    {
        return $this->authRepository->register($request);
    }

    public function login($request)
    {
        try{
            $credentials = Arr::only($request, ['email', 'password']);
            #Check for the "Remember Me" option
            $remember = isset($request['remember_me']) ? true : false;
            if(!$token = Auth::guard('sellerApi')->attempt($credentials,$remember))
                return $this->ApiErrorResponse('','Invalid credentials');
            return $this->ApiSuccessResponseAndToken(AuthResource::make(auth('sellerApi')->user()),'success message',$token);
        }catch(\Exception $ex){
            return $this->ApiErrorResponse($ex->getMessage(),__('admin.general_error'));
        }
    }
    public function profile()
    {
        try{
            $seller = auth('sellerApi')->user();
            if($seller)
                return $this->ApiSuccessResponse(AuthResource::make($seller));
        }catch(\Exception $ex){
            return $this->authRepository->ApiErrorResponse($ex->getMessage(),__('admin.general_error'));
        }
    }
    public function logout()
    {
        auth()->guard('sellerApi')->logout();
        return $this->ApiSuccessResponse([],trans('seller.auth.logout_message'));
    }
    public function generateG2FAuth()
    {
        try{
            $data = [];
            // Instantiate Google2FA
            $google2fa = new Google2FA();
            // Generate the secret key for the user
            $data['secret'] = $google2fa->generateSecretKey();
            // You can save the secret key for the user in the database (for example, in a 'two_factor_secret' field)
            $user = Auth::guard('sellerApi')->user();
            $user->google2fa_secret = $data['secret'];
            $user->save();
            // Generate the URL for the QR code (Google Authenticator compatible)
            $data['qrCodeUrl'] = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $data['secret']
            );
            // Return the secret key and QR code URL (or the QR code itself)
            return $this->authRepository->ApiSuccessResponse($data,'Qr Generated Successfully');
        }catch(\Exception $ex){
            return $this->authRepository->ApiErrorResponse($ex->getMessage(),__('admin.general_error'));
        }
    }
    public function updateProfile($request)
    {
        try{
            DB::beginTransaction();
            $seller = auth('sellerApi')->user();
            // Check if username, mobile_number, or email exists in the database
            if(Seller::where('owner_name',$request['owner_name'])->where('id','!=',$seller->id)->exists()){
                return $this->ApiErrorResponse([],trans('seller.validations.unique_name'));
            }
            $this->authRepository->updateSeller($seller,$request);
            DB::commit();
            return $this->ApiSuccessResponse(SellerResource::make($seller),'update seller data success');
        }catch(\Exception $ex)
        {
            DB::rollBack();
            return $this->ApiErrorResponse($ex->getMessage(),trans('admin.general_error'));
        }
    }

}
