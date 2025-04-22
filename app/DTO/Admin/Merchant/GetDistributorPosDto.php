<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 * Class GetDistributorPosDto
 * @package App\DTO\Admin\Merchant
 */
class GetDistributorPosDto extends BaseDTO
{
    /**
     * @var string
     */
    protected string $merchant_id;

    /**
     * @var string|null
     */
    protected ?string $branch_name = null;

    /**
     * @var string|null
     */
    protected ?string $address = null;

    /**
     * @var string|null
     */
    protected ?string $receiver_phone = null;

    /**
     * @var string|null
     */
    protected ?string $receiver_name = null;

    /**
     * @var string|null
     */
    protected ?string $pos_terminal_id = null;

    /**
     * Automatically map properties, including nested filters.
     *
     * @param Request $request
     */
    protected function autoMapProperties(Request $request): void
    {
        parent::autoMapProperties($request);

        if ($request->has('filters')) {
            $filters = $request->input('filters');

            $this->branch_name = $filters['branch_name'] ?? null;
            $this->address = $filters['address'] ?? null;
            $this->receiver_phone = $filters['receiver_phone'] ?? null;
            $this->receiver_name = $filters['receiver_name'] ?? null;
            $this->pos_terminal_id = $filters['pos_terminal_id'] ?? null;
        }
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * @return string|null
     */
    public function getBranchName(): ?string
    {
        return $this->branch_name;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getReceiverPhone(): ?string
    {
        return $this->receiver_phone;
    }

    /**
     * @return string|null
     */
    public function getReceiverName(): ?string
    {
        return $this->receiver_name;
    }

    /**
     * @return string|null
     */
    public function getPosTerminalId(): ?string
    {
        return $this->pos_terminal_id;
    }
}
