<?php

namespace App\DTO\Admin\BalanceRequest;

use App\DTO\BaseDTO;
use App\Http\Requests\Admin\BalanceRequest\UpdateBalanceRequest;
use App\Http\Requests\Pos\BalanceRequest\RequestBalanceRequest;

class BalanceRequestStatusDto extends BaseDTO
{
    protected string $status;

    public function __construct(UpdateBalanceRequest $request)
    {
        parent::__construct($request);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
