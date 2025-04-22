<?php

namespace App\Adapters\PaymentGateways;

class MadaTransactionAdapter
{
    /**
     * @var
     */
    protected $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data['madaTransactionResult'];
    }

    /**
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->data['Amounts']['PurchaseAmount'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getRRN(): ?string
    {
        return $this->data['RRN'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getStatusCode(): ?string
    {
        return $this->data['StatusCode'] ?? null;
    }
}
