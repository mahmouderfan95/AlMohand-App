<?php

namespace App\Repositories\Distributor;

use App\Models\Distributor\Distributor;
use App\Models\Distributor\DistributorGroup;
use App\Models\Distributor\DistributorGroupCondition;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;


class DistributorGroupConditionRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return DistributorGroupCondition::class;
    }
}
