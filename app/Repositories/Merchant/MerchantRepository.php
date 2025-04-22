<?php

namespace App\Repositories\Merchant;

use App\Models\Merchant\Merchant;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;

class MerchantRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return Merchant::class;
    }

    public function showByPhone($phone)
    {
        return $this->model->where('phone', $phone)->first();
    }

    public function verify($merchant): bool
    {
        $merchant->verified_at = now();
        $merchant->save();
        return true;
    }

    public function updatePassword($id, $requestData)
    {
        $merchant = $this->model->find($id);
        $merchant->password = Hash::make($requestData->new_password);
        $merchant->save();
        return true;
    }
}
