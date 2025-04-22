<?php

namespace App\Repositories\Order;

use App\Models\Order\OrderProductOption;
use App\Repositories\Option\OptionValueRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderProductOptionRepository extends BaseRepository
{
    public function __construct(
        Application $app,
        private OrderProductOptionValueRepository $orderProductOptionValueRepository,
        private OptionValueRepository $optionValueRepository,
    )
    {
        parent::__construct($app);
    }

    public function store($requestData, $product, $orderProductId)
    {
        $insertArray = [];
        foreach ($product->product_options as $productOption){
            // prepare data
            $data = [
                'order_product_id' => $orderProductId,
                'product_option_id' => $productOption->id,
                'value' => null,
            ];
            // check this product_option required if true must exist in request
            $optionExist = null;
            foreach ($requestData['product_options'] as $requestOption){
                if ($requestOption['id'] == $productOption->id){
                    $optionExist = $requestOption;
                    break;
                }
            }
            if (! $optionExist)
                return false;
            if ($productOption->required == 1 && ! $optionExist)
                return false;
            // check if option have option_values like select-box to take option_value_ids
            // if not have i will take value from request
            $optionValueIds = [];
            if (count($productOption->option->option_values) > 0 && isset($optionExist['option_value_ids'])){
                $optionValueIds = $this->optionValueRepository->optionValueIds($optionExist['option_value_ids'], $productOption->option->id);
            }elseif(count($productOption->option->option_values) == 0 && isset($optionExist['value'])){
                $data['value'] = $optionExist['value'];
            }else{
                return false;
            }

            // Check for existing order_product_options
            $orderProductOption = $this->model
                ->where('order_product_id', $orderProductId)
                ->where('product_option_id', $productOption->id)
                ->first();

            if ($orderProductOption)
                $orderProductOption->update($data);
            else
                $orderProductOption = $this->model->create($data);

            // check if $optionValueIds exist to store it in new table ( order_product_option_values )
            if (count($optionValueIds) > 0)
                $this->orderProductOptionValueRepository->store($orderProductId, $orderProductOption->id, $optionValueIds);

            $insertArray[] = $orderProductOption;
        }

        return $insertArray;
    }




    public function model(): string
    {
        return OrderProductOption::class;
    }
}
