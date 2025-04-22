<?php

namespace App\DTO\Admin\Merchant;

use App\DTO\BaseDTO;
use Illuminate\Http\Request;

/**
 *
 */
class CreateMerchantDto extends BaseDTO
{
    /**
     * @var int|null
     */
    protected ?int $distributor_group_id = null;
    /**
     * @var int
     */
    protected int $zone_id;
    /**
     * @var int
     */
    protected int $sales_rep_id;
    /**
     * @var int
     */
    protected int $city_id;
    /**
     * @var string
     */
    protected string $manager_name;
    /**
     * @var string
     */
    protected string $owner_name;

    /**
     * @var string
     */
    protected string $phone;

    /**
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * @var string|null
     */
    protected ?string $address;

    /**
     * @var string
     */
    protected string $commercial_register;

    /**
     * @var string
     */
    protected string $tax_card;
    /**
     * @var array
     */
    protected array $translations;
    /**
     * @var array|null
     */
    protected ?array $commercial_register_files = [];
    /**
     * @var array|null
     */
    protected ?array $tax_card_files = [];
    /**
     * @var array|null
     */
    protected ?array $identity_files = [];
    /**
     * @var array|null
     */
    protected ?array $more_files = [];


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
    public function getCommercialRegisterFiles(): array
    {
        return $this->commercial_register_files;
    }

    public function setCommercialRegisterFiles(?array $commercial_register_files): void
    {
        $this->commercial_register_files = $commercial_register_files ?? [];
    }

    /**
     * @return array
     */
    public function getTaxCardFiles(): array
    {
        return $this->tax_card_files;
    }

    public function setTaxCardFiles(?array $tax_card_files): void
    {
        $this->tax_card_files = $tax_card_files ?? [];
    }

    /**
     * @return array
     */
    public function getIdentityFiles(): array
    {
        return $this->identity_files;
    }


    public function setIdentityFiles(?array $identity_files): void
    {
        $this->identity_files = $identity_files ?? [];
    }

    /**
     * @return array
     */
    public function getMoreFiles(): array
    {
        return $this->more_files;
    }

    public function setMoreFiles(?array $more_files): void
    {
        $this->more_files = $more_files;
    }

    /**
     */
    public function getDistributorGroupId(): ?int
    {
        return $this->distributor_group_id;
    }

    /**
     * @param int|null $distributor_group_id
     * @return void
     */
    public function setDistributorGroupId(?int $distributor_group_id): void
    {
        $this->distributor_group_id = $distributor_group_id;
    }

    /**
     * @return int
     */
    public function getZoneId(): int
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     * @return void
     */
    public function setZoneId(int $zone_id): void
    {
        $this->zone_id = $zone_id;
    }
    /**
     * @return int
     */
    public function getSalesRepId(): int
    {
        return $this->sales_rep_id;
    }

    /**
     * @param int $sales_rep_id
     * @return void
     */
    public function setSalesRepId(int $sales_rep_id): void
    {
        $this->sales_rep_id = $sales_rep_id;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->city_id;
    }

    /**
     * @param int $city_id
     * @return void
     */
    public function setCityId(int $city_id): void
    {
        $this->city_id = $city_id;
    }

    /**
     * @return string
     */
    public function getManagerName(): string
    {
        return $this->manager_name;
    }

    /**
     * @param string $manager_name
     * @return void
     */
    public function setManagerName(string $manager_name): void
    {
        $this->manager_name = $manager_name;
    }

    /**
     * @return string
     */
    public function getOwnerName(): string
    {
        return $this->owner_name;
    }

    /**
     * @param string $owner_name
     * @return void
     */
    public function setOwnerName(string $owner_name): void
    {
        $this->owner_name = $owner_name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    /**
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email ?? null;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return void
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCommercialRegister(): string
    {
        return $this->commercial_register;
    }

    /**
     * @param string $commercial_register
     * @return void
     */
    public function setCommercialRegister(string $commercial_register): void
    {
        $this->commercial_register = $commercial_register;
    }

    /**
     * @return string
     */
    public function getTaxCard(): string
    {
        return $this->tax_card;
    }

    /**
     * @param string $tax_card
     * @return void
     */
    public function setTaxCard(string $tax_card): void
    {
        $this->tax_card = $tax_card;
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
