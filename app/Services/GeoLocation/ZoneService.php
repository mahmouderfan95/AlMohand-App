<?php

namespace App\Services\GeoLocation;

use App\DTO\BaseDTO;
use App\Http\Resources\Admin\ZoneResource;
use App\Interfaces\ServicesInterfaces\GeoLocation\ZoneServiceInterface;
use App\Repositories\GeoLocation\ZoneRepository;
use App\Services\BaseService;

class ZoneService extends BaseService implements ZoneServiceInterface
{
    public function __construct(private readonly ZoneRepository $zoneRepository)
    {

    }

    public function index(array $filter): mixed
    {
        return $this->listResponse(ZoneResource::collection($this->zoneRepository->with('translations')->get()));
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
