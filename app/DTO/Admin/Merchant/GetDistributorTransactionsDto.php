<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 * Class GetPosTerminalTransactionDto
 * @package App\DTO\Admin\PosTerminal
 */
class GetDistributorTransactionsDto extends BaseDTO
{
    /**
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * @var float|null
     */
    protected ?float $amount = null;

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

            $this->description = $filters['description'] ?? null;
            $this->type = $filters['type'] ?? null;
            $this->amount = isset($filters['amount']) ? (float) $filters['amount'] : null;
        }
    }


    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }
}
