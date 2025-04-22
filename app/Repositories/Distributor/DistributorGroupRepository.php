<?php

namespace App\Repositories\Distributor;

use App\Models\Distributor\Distributor;
use App\Models\Distributor\DistributorGroup;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;


class DistributorGroupRepository extends BaseRepository
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
        return DistributorGroup::class;
    }
}
