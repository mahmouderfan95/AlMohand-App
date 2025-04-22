<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 *
 */
class CreateMerchantGroupDto extends BaseDTO
{
    /**
     * @var bool
     */
    protected ?bool $is_active;
    /**
     * @var bool
     */
    protected bool $is_auto_assign;
    /**
     * @var bool|null
     */
    protected ?bool $is_require_all_conditions;

    /**
     * @var array
     */
    protected array $translations;
    /**
     * @var array
     */
    protected array $conditions;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return void
     */
    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }


    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return void
     */
    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }

    /**
     * @return bool
     */
    public function getIsAutoAssign(): bool
    {
        return $this->is_auto_assign;
    }

    /**
     * @param bool $is_auto_assign
     * @return void
     */
    public function setIsAutoAssign(bool $is_auto_assign): void
    {
        $this->is_auto_assign = $is_auto_assign;
    }

    /**
     * @return bool|null
     */
    public function getIsRequireAllConditions(): ?bool
    {
        return $this->is_require_all_conditions;
    }

    /**
     * @param bool|null $is_require_all_conditions
     * @return void
     */
    public function setIsRequireAllConditions(?bool $is_require_all_conditions): void
    {
        $this->is_require_all_conditions = $is_require_all_conditions;
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     * @return void
     */
    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }
}
