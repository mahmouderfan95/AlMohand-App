<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 * Class GetDistributorPosDto
 * @package App\DTO\Admin\Merchant
 */
class UpdateBalanceDto extends BaseDTO
{

    protected $type;

    /**
     * @var float
     */
    protected float $amount;

    /**
     * @var string|null
     */
    protected ?string $description = null;


    /**
     * Automatically map properties, including nested filters.
     *
     * @param Request $request
     */
    protected function autoMapProperties(Request $request): void
    {
        parent::autoMapProperties($request);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
