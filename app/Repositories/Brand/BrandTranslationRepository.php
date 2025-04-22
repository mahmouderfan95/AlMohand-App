<?php

namespace App\Repositories\Brand;

use App\Models\Brand\BrandTranslation;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandTranslationRepository extends BaseRepository
{
    public function __construct(
        Application $app,
        private LanguageRepository $languageRepository
    )
    {
        parent::__construct($app);
    }

    public function storeOrUpdate($requestData, $brand_id)
    {
        $languages = $this->languageRepository->getAllLanguages();
        foreach ($languages as $language) {
            $languageId = $language->id;
            $this->model->updateOrCreate(
                [
                    'brand_id' => $brand_id,
                    'language_id' => $languageId,
                ],
                [
                    'name' => $requestData->name[$languageId],
                    'description' => $requestData->description[$languageId] ?? null,
                ]
            );
        }
        return true;
    }

    public function deleteByBrandId($brand_id)
    {
        return $this->model->where('brand_id',$brand_id)->delete();
    }
    /**
     * Brand Model
     *
     * @return string
     */
    public function model(): string
    {
        return BrandTranslation::class;
    }
}
