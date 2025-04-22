<?php

namespace App\Interfaces\ServicesInterfaces;

use App\DTO\BaseDTO;
use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface
{
    /**
     * @return mixed
     */
    public function index(array $filter): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function show($id) :mixed;

    /**
     * @param BaseDto $data
     * @return mixed
     */
    public function store(BaseDto $data): mixed;

    /**
     * @param $id
     * @param BaseDTO $data
     * @return mixed
     */
    public function update($id, BaseDto $data): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed;

    public function getTrashed();

    public function restore($id);

    public function bulkDelete(array $ids = []);
}
