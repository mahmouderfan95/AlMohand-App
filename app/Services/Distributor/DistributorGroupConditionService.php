<?php

namespace App\Services\Distributor;

use App\DTO\BaseDTO;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupConditionServiceInterface;
use App\Repositories\Distributor\DistributorGroupConditionRepository;
use App\Services\BaseService;

class DistributorGroupConditionService extends BaseService implements DistributorGroupConditionServiceInterface
{

    public function __construct(private readonly DistributorGroupConditionRepository $distributorGroupConditionRepository)
    {

    }

    public function index(array $filter): mixed
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
