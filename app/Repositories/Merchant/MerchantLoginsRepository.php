<?php

namespace App\Repositories\Merchant;

use App\Models\Merchant\Merchant;
use App\Models\Merchant\MerchantLogins;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class MerchantLoginsRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return MerchantLogins::class;
    }
}
