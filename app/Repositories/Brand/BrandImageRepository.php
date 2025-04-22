<?php

namespace App\Repositories\Brand;

use App\Helpers\FileUpload;
use App\Models\Brand\BrandImage;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandImageRepository extends BaseRepository
{
    use FileUpload;
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }


    public function storeOrUpdate($requestData, $brandId)
    {
        if (isset($requestData->images_data))
            foreach ($requestData->images_data as $imageData) {
                $this->model->updateOrCreate(
                    [
                        'brand_id' => $brandId,
                        'key' => $imageData['key'],
                    ],
                    [
                        'image' => $this->save_file($imageData['image'], 'brands'),
                    ]
                );
            }
    }





    public function model(): string
    {
        return BrandImage::class;
    }
}
