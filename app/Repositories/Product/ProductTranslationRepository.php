<?php

namespace App\Repositories\Product;

use App\Models\Product\ProductTranslation;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductTranslationRepository extends BaseRepository
{

    public function __construct(
        Application $app,
        private LanguageRepository $languageRepository
    )
    {
        parent::__construct($app);
    }

    public function store($data_request, $product_id)
    {
        $languages = $this->languageRepository->getAllLanguages();
        foreach ($languages as $language) {
            $languageId = $language->id;
            $this->model->create([
                'product_id' => $product_id,
                'language_id' => $languageId,
                'name' => $data_request['name'][$languageId],
                'desc' => $data_request['desc'][$languageId] ?? null,
                // 'meta_title' => $data_request['meta_title'][$languageId] ?? null,
                // 'meta_keyword' => $data_request['meta_keyword'][$languageId] ?? null,
                // 'meta_description' => $data_request['meta_description'][$languageId] ?? null,
                'content' => $data_request['content'][$languageId] ?? null,
                'receipt_content' => $data_request['receipt_content'][$languageId] ?? null,
            ]);
        }
        return true;
    }

    public function deleteByProductId($product_id)
    {
        return $this->model->where('product_id', $product_id)->delete();
    }

    /**
     * Product Model
     *
     * @return string
     */
    public function model(): string
    {
        return ProductTranslation::class;
    }
}
