<?php

namespace App\Repositories\PosTerminal;

use App\Models\BalanceRequest\BalanceRequest;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;


class BalanceRequestRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function getRequestByPaymentCode($payment_code, $amount)
    {
        return $this->model->query()->where('code', '=', $payment_code)->where('amount', '=', $amount)->first();
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return BalanceRequest::class;
    }
}
