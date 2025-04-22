<?php

namespace App\Repositories\SalesRepUser;

use App\Models\SalesRep\SalesRepLocation;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class SalesRepUserLocationRepository extends BaseRepository
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Store or update locations for a given SalesRep user.
     *
     * @param array $locations
     * @param int $salesRepUserId
     * @return bool
     */
    public function storeOrUpdate(array $locations, int $salesRepUserId): bool
    {
        $this->deleteBySalesRepUserId($salesRepUserId);

        $locationData = array_map(function ($location) use ($salesRepUserId) {
            return [
                'sales_rep_id' => $salesRepUserId,
                'city_id' => $location['city_id'],
                'region_id' => $location['region_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $locations);

        return $this->model->insert($locationData);
    }

    /**
     * Delete locations by SalesRep user ID.
     *
     * @param int $salesRepUserId
     * @return int
     */
    public function deleteBySalesRepUserId(int $salesRepUserId): int
    {
        return $this->model->where('sales_rep_id', $salesRepUserId)->delete();
    }

    /**
     * Define the model class.
     *
     * @return string
     */
    public function model(): string
    {
        return SalesRepLocation::class;
    }
}
