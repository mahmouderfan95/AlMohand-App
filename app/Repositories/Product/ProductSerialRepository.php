<?php

namespace App\Repositories\Product;

use App\Enums\ProductSerialType;
use App\Models\Invoice;
use App\Models\Order\OrderProduct;
use App\Models\Product\ProductSerial;
use App\Repositories\Order\TempStockRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductSerialRepository extends BaseRepository
{

    public function __construct(
        Application                     $app,
        private TempStockRepository     $tempStockRepository,
    )
    {
        parent::__construct($app);
    }

    public function GetFirstExpireFreeSerialsFromProcedure(OrderProduct $orderProduct)
    {
        // Call the stored procedure (executes immediately)
        // DB::statement('CALL update_product_serials(?, ?, ?)', [$orderProduct->order_id, $orderProduct->product_id, $orderProduct->quantity]);
        // get these presold serials
        $productSerialIds = $this->tempStockRepository->getReservedStock($orderProduct);
        Log::info($productSerialIds);
        if (! $productSerialIds || count($productSerialIds) == 0) {
            return false;
        }

        $productSerials = $this->model
            ->whereIn('id', $productSerialIds)
            ->where('status', ProductSerialType::getTypePresold())
            ->get();

        return $productSerials;
    }

    public function changeSerialsToSold($serialIds)
    {
        Log::info($serialIds);
        return $this->model
            ->whereIn('id', $serialIds)
            ->update(['status' => ProductSerialType::getTypeSold()]);
    }

    public function storeSerials($requestData, $invoiceId)
    {
        $uniqueProductSerials = [];
        $repeatedSerials = [];
        $encounteredSerials = [];

        foreach ($requestData->product_serials as $productSerial) {
            $serial = $productSerial['serial'];

            $checkAvailability = $this->checkSerialAvailability($requestData->product_id, $serial);
            if ($checkAvailability || in_array($serial, $encounteredSerials)) {
                $repeatedSerials[] = $productSerial;
            } else {
                $uniqueProductSerials[] = $productSerial;
                $encounteredSerials[] = $serial;
                $this->model->create([
                    'invoice_id' => $invoiceId,
                    'product_id' => $requestData->product_id,
                    'serial' => $productSerial['serial'],
                    'scratching' => $productSerial['scratching'],
                    'status' => $requestData->status,
                    'source_type' => $requestData->source_type,
                    'buying' => $requestData->buying,
                    'expiring' => $requestData->expiring,
                    'price_before_vat' => $productSerial->price_before_vat ?? 0,
                    'vat_amount' => $productSerial->vat_amount ?? 0,
                    'price_after_vat' => $productSerial->price_after_vat ?? $requestData->product_price ?? 0,
                    'currency' => $productSerial->currency ?? $requestData->current_currency ?? null,
                ]);
            }

        }

        return [
            'uniqueProductSerials' => $uniqueProductSerials,
            'repeatedSerials' => $repeatedSerials
        ];
    }

    public function store($requestData)
    {
        $this->model->insert($requestData);
        $lastInserted = $this->model->orderBy('id', 'desc')->take(count($requestData))->get();
        return $lastInserted;
    }

    public function update_stock_logs($id,$data_request_status)
    {
        $productSerial = $this->model->where('id',$id)->first();
        $productSerial->status = $data_request_status;
        $productSerial->save();
        // Now $productSerials will contain all data grouped by invoice_id with count
        return $productSerial;
    }
    public function stock_logs()
    {
        return Invoice::with(['vendor:id,name','product'])->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function stock_logs_invoice($invoice_id)
    {
        $logs = $this->model->where('invoice_id', $invoice_id)->paginate(PAGINATION_COUNT_ADMIN);

        $logs->getCollection()->transform(function ($log) {
            $log->scratching = substr($log->scratching, 0, 3) . '***';
            return $log;
        });

        return $logs;
    }

    public function ChangeInvoiceSerialStatus($requestData)
    {
        $invoice = Invoice::where('id', $requestData->invoice_id)->first();
        $invoice->status = $requestData->status;
        $invoice->save();
        return $this->model
            ->where('invoice_id', $requestData->invoice_id)
            ->whereIn('status', [ProductSerialType::getTypeHold(), ProductSerialType::getTypeFree(), ProductSerialType::getTypeStopped()])
            ->update([
                'status' => $requestData->status,
            ]);
    }


    public function checkSerialAvailability($productId, $serial)
    {
        return $this->model
            ->where('product_id', $productId)
            ->where('serial', $serial)
            ->first();
    }

    public function model(): string
    {
        return ProductSerial::class;
    }
}
