<?php

namespace App\Repositories\Product;

use App\Models\Product\ProductPriceDistributorGroup;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductPriceDistributorGroupRepository extends BaseRepository
{


    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
    public function store($requestData)
    {
        Log::info("ProductPriceDistributorGroupRepository::store");
        Log::info($requestData);
        if (count($requestData['merchant_group']) == 0 ) {
            return false;
        }
        foreach ($requestData['merchant_group'] as $distributorGroup) {
            if (
                $distributorGroup['price'] < $requestData['cost_price'] ||
                $distributorGroup['price'] < $requestData['wholesale_price']
            ) {
                return false;
            }
            $this->model->create([
                'product_id' => $requestData['product_id'],
                'distributor_group_id' => $distributorGroup['id'],
                'price' => $distributorGroup['price'],
            ]);
        }
        return true;
    }
    public function deleteByProductId($productId)
    {
        $this->model->where('product_id', $productId)->delete();
        return true;
    }
    public function model(): string
    {
        return ProductPriceDistributorGroup::class;
    }
}
