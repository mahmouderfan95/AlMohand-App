<?php

namespace App\Repositories\GeoLocation;

use App\Models\GeoLocation\CityTranslation;
use Prettus\Repository\Eloquent\BaseRepository;

class CityTranslationRepository extends BaseRepository
{

    public function store($data_request, $city_id)
    {
        foreach ($data_request as $language_id => $value) {
             $this->model->create(
                [
                    'city_id' => $city_id,
                    'language_id' =>$language_id ,
                    'name' => $value,
                ]);
        }
        return true;
    }

    public function deleteByCityId($city_id)
    {
        return $this->model->where('city_id',$city_id)->delete();
    }
    /**
     * City Model
     *
     * @return string
     */
    public function model(): string
    {
        return CityTranslation::class;
    }
}
