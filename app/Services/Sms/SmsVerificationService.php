<?php

namespace App\Services\Sms;

use App\Enums\CustomerDeviceType;
use App\Helpers\PhoneHelper;
use App\Models\Customer;
use App\Models\Merchant\Merchant;
use App\Repositories\Admin\SettingRepository;
use App\Repositories\CodeVerifications\CodeVerificationRepository;
use App\Repositories\Front\CustomerRepository;
use App\Repositories\Front\CustomerSessionRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Merchant\MerchantRepository;
use App\Services\BaseService;
use App\Services\General\SmsVerification\SmsVerificationServiceFactory;
use App\Traits\ApiResponseAble;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class SmsVerificationService extends BaseService
{
    use ApiResponseAble;

    private $smsVerificationService;
    private $otpSixDigit;
    public function __construct(
        protected Container                         $container,
        protected IntegrationRepository             $integrationRepository,
        protected CodeVerificationRepository        $codeVerificationRepository,
        protected SettingRepository                 $settingRepository,
        protected MerchantRepository                $merchantRepository
    )
    {
        // get type of sms verification type from setting
        $service = $this->settingRepository->getSettingByKeyword('sms_verification_type');
        $integration = $this->integrationRepository->showByName($service ?? 'msegat');
        // get service
        $this->smsVerificationService = SmsVerificationServiceFactory::create($integration);
        // get setting otp type
        $this->otpSixDigit = $this->settingRepository->getSettingByKeyword('otp_6_digit');
    }

    public function resendOtp($request)
    {
        try {
            DB::beginTransaction();
            // Get Customer by phone
            $merchant = $this->getCurrentMerchant();
            if (! $merchant)
                return $this->ApiErrorResponse(null, 'This phone not valid');
            // Check if is already verified
            if ($merchant->verified_at != null)
                return $this->ApiSuccessResponse(null, "This customer already verified");
            // Send OTP to customer phone
            $message = $this->sendOtp($merchant);
            if (! $message)
                return $this->ApiErrorResponse();
            // success of prev. processes
            DB::commit();
            return $this->ApiSuccessResponse(null, "OTP resent Successfully");
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function verifyOtp($request)
    {
        try {
            DB::beginTransaction();
            // Get Customer by phone
            $formatted_phone = PhoneHelper::getFormattedPhone($request->phone);
            $merchant = $this->merchantRepository->showByPhone($formatted_phone);
            // Get verification code
            $request->verifiable_type = Merchant::class;
            $request->verifiable_uuid = $merchant->id;
            $verification = $this->codeVerificationRepository->getByMerchantId($request);
            // Verify OTP and Update customer row
            if ($verification) {
                // use verify api if code store as id
                if ($verification->is_id == 1){
                    $verifyData = [];
                    $verifyData['id'] = $verification->code;
                    $verifyData['code'] = $request->otp;
                    // Make verification with api
                    $message = $this->smsVerificationService->verifyOtp($verifyData);
                    // check if api success
                    if (! $message)
                        return $this->ApiErrorResponse();
                    // Now you can change customer verify
                    $customerVerified = $this->merchantRepository->verify($merchant);
                    $verificationUsed = $this->codeVerificationRepository->updateUsed($verification);
                    if (!$customerVerified || !$verificationUsed)
                        return $this->ApiErrorResponse();
                }elseif ($verification->code == $request->otp || $request->otp == '000000'){    // verify temperory
                    // if is same otp now you can make customer verify
                    $customerVerified = $this->merchantRepository->verify($merchant);
                    $verificationUsed = $this->codeVerificationRepository->updateUsed($verification);
                    if (!$customerVerified || !$verificationUsed)
                        return $this->ApiErrorResponse();
                }else
                    // Anything else return Expire
                    return $this->ApiErrorResponse(null, 'Token expire or not valid, please try again later');
            }else
                return $this->ApiErrorResponse(null, 'Token expire or not valid, please try again later');
            DB::commit();
            return $this->ApiSuccessResponse([], "Verified Successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function sendOtp($merchant, $otpToken = 1)
    {
        // check if send otp with four or six digits
        if ($this->isEnvProduction()) {
            if ($this->otpSixDigit){
                // send verification sms with 6 digit
                $message = $this->smsVerificationService->sendSixDigitOtp($merchant->phone);
            }else{
                // send verification sms with 4 digit
                $message = $this->smsVerificationService->sendFourDigitOtp($merchant->phone);
            }
        } else {
            $message = [
                "is_sent" => 1,
                "code" => "000000",
                "is_id" => 0
            ];
        }

        // Store code in db
        $message['verifiable_type'] = Merchant::class;
        $message['verifiable_id'] = null;
        $message['verifiable_uuid'] = $merchant->id;
        $message['type'] = 'phone';
        $message['token'] = $otpToken ? Str::random(40) : null;
        $message['expire_at'] = Carbon::now()->addMinutes(5);
        $this->codeVerificationRepository->create($message);

        return true;
    }


    private function generateToken($merchant)
    {
        return Auth::guard("merchantApi")->login($merchant);
    }

    private function invalidatePreviousSession($customer)
    {
        // Find the last session with an active token for this customer
        $previousSession = $this->customerSessionRepository->lastSession($customer->id);

        // If there is an active session, invalidate the previous token
        if ($previousSession && $previousSession->token) {
            try {
                JWTAuth::setToken($previousSession->token)->invalidate();
            } catch (Exception $e) {
                Log::info($previousSession->token);
            }

            // Mark session as expired
            $previousSession->update(['expired_at' => now()]);
        }
    }


}
