<?php

namespace App\Repositories\Currency;

use App\Models\Currency\CurrencyTranslation;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class CurrencyTranslationRepository extends BaseRepository
{
    public function __construct(
        Application $app,
        private LanguageRepository $languageRepository
    )
    {
        parent::__construct($app);
    }

    public function storeOrUpdate($requestData, $currencyId)
    {
        $languages = $this->languageRepository->getAllLanguages();
        foreach ($languages as $language) {
            $languageId = $language->id;
            $this->model->updateOrCreate(
                [
                    'currency_id' => $currencyId,
                    'language_id' => $languageId,
                ],
                [
                    'name' => $requestData->name[$languageId],
                    'code' => $requestData->code[$languageId],
                ]
            );
        }
        return true;
    }

    public function deleteByCurrencyId($currencyId)
    {
        return $this->model->where('currency_id', $currencyId)->delete();
    }



    public function model(): string
    {
        return CurrencyTranslation::class;
    }
}
