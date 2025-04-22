<?php

namespace App\Repositories\GeoLocation;

use App\Models\GeoLocation\Region;
use App\Models\GeoLocation\Zone;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;

class ZoneRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Region Model
     *
     * @return string
     */
    public function model(): string
    {
        return Zone::class;
    }
}
