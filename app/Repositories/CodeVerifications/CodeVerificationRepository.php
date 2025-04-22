<?php

namespace App\Repositories\CodeVerifications;

use App\Models\CodeVerification;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class CodeVerificationRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function create($attributes)
    {
        return $this->model->updateOrCreate(
            [
                'verifiable_type' => $attributes['verifiable_type'],
                'verifiable_id' => $attributes['verifiable_id'],
                'verifiable_uuid' => $attributes['verifiable_uuid'],
                'type' => $attributes['type'],
            ],
            $attributes
        );
    }

    public function getByMerchantId($requestData)
    {
        return $this->model->query()
            ->where('verifiable_type', $requestData->verifiable_type)
            ->where('verifiable_uuid', $requestData->verifiable_uuid)
            ->where('expire_at', '>=', Carbon::now())
            ->where('used', 0)
            ->first();
    }

    public function getByResetToken($requestData)
    {
        return $this->model
            ->where('verifiable_type', $requestData->verifiable_type)
            ->where('verifiable_uuid', $requestData->verifiable_uuid)
            ->where('expire_at', '>=', Carbon::now())
            ->where('token', $requestData->reset_token)
            ->where('used', 1)
            ->first();
    }

    public function updateUsed(CodeVerification $verification)
    {
        $verification->used = 1;
        $verification->save();
        return true;
    }

    public function updateToken(CodeVerification $verification)
    {
        $verification->token = Str::random(40);
        $verification->expire_at = Carbon::now()->addMinutes(5);
        $verification->save();
        return $verification;
    }

    /**
     * CodeVerification Model
     *
     * @return string
     */
    public function model(): string
    {
        return CodeVerification::class;
    }
}
