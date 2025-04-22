<?php

namespace App\Repositories\GeoLocation;

use App\Models\GeoLocation\RegionTranslation;
use Prettus\Repository\Eloquent\BaseRepository;

class RegionTranslationRepository extends BaseRepository
{

    public function store($data_request, $region_id)
    {
        foreach ($data_request as $language_id => $value) {
             $this->model->create(
                [
                    'region_id' => $region_id,
                    'language_id' =>$language_id ,
                    'name' => $value,
                ]);
        }
        return true;
    }

    public function deleteByRegionId($region_id)
    {
        return $this->model->where('region_id',$region_id)->delete();
    }
    /**
     * Region Model
     *
     * @return string
     */
    public function model(): string
    {
        return RegionTranslation::class;
    }
}
