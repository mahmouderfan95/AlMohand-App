<?php

namespace App\Services\Admin;

use App\Repositories\Product\ProductRepository;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Support\Facades\DB;

class HomeService
{
    use ApiResponseAble;

    public function __construct(
        private ProductRepository           $productRepository,
    )
    {}

    public function index($request)
    {
        try {
            DB::beginTransaction();
            $data = [];
            $request->query->set('page', $request->input('topup_orders_page', 1));
            //$request->query->set('page', $request->input('all_orders_page', 1));
            //$data['all_orders'] = $this->orderRepository->getAllOrders($request);
            $request->query->set('page', $request->input('stock_almost_out_page', 1));
            $data['stock_almost_out'] = $this->productRepository->stockAlmostOut($request);

            DB::commit();
            return $this->ApiSuccessResponse($data, 'Home Data...!');
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }




}
