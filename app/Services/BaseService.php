<?php

namespace App\Services;

use App\Helpers\FileUpload;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\Auth;

class BaseService
{
    use ApiResponseAble, FileUpload;

    public function getCurrentMerchant(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('merchantApi')->user();
    }

    public function getCurrentPos(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('posApi')->user();
    }

    public function getCurrentAdmin(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('adminApi')->user();
    }

    public function isEnvProduction(): bool
    {
        if (app()->environment('local')) {
            return false;
        }
        return true;
    }
}
