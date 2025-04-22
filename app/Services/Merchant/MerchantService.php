<?php

namespace App\Services\Merchant;

use App\DTO\BaseDTO;
use App\Interfaces\ServicesInterfaces\Merchant\MerchantServiceInterface;
use App\Repositories\Merchant\MerchantLoginsRepository;
use App\Repositories\Merchant\MerchantRepository;
use App\Services\BaseService;
use App\Services\Sms\SmsVerificationService;

class MerchantService extends BaseService implements MerchantServiceInterface
{

    public function __construct(private MerchantRepository $merchantRepository,
                                protected SmsVerificationService $smsVerificationService,
                                private MerchantLoginsRepository $merchantLoginsRepository)
    {

    }

    public function index($filter): mixed
    {
        // TODO: Implement index() method.
    }

    public function show($id): mixed
    {
        // TODO: Implement show() method.
    }

    public function store(BaseDTO $data): mixed
    {
        // TODO: Implement store() method.
    }

    public function update($id, BaseDTO $data): mixed
    {
        // TODO: Implement update() method.
    }

    public function delete($id): mixed
    {
        // TODO: Implement delete() method.
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }
}
