<?php

namespace App\Repositories\SalesRepLevel;

use App\Models\SalesRepLevel\SalesRepLevelTranslation;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class SalesRepLevelTranslationRepository extends BaseRepository
{
    public function __construct(
        Application $app,
        private LanguageRepository $languageRepository
    )
    {
        parent::__construct($app);
    }


    public function storeOrUpdate($requestData, $salesRepLevelId)
    {
        $languages = $this->languageRepository->getAllLanguages();
        foreach ($languages as $language) {
            $languageId = $language->id;
            $this->model->updateOrCreate(
                [
                    'sales_rep_level_id' => $salesRepLevelId,
                    'language_id' => $languageId,
                ],
                [
                    'name' => $requestData['name'][$languageId],
                ]
            );
        }
        return true;
    }

    public function deleteBySalesRepLevelId($salesRepLevel_id)
    {
        return $this->model->where('sales_rep_level_id',$salesRepLevel_id)->delete();
    }
    /**
     * SalesRepLevel Model
     *
     * @return string
     */
    public function model(): string
    {
        return SalesRepLevelTranslation::class;
    }
}
