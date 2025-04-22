<?php

namespace App\Console\Commands;

use App\Enums\ProductSerialType;
use App\Models\Order\TempStock;
use App\Models\Product\ProductSerial;
use Illuminate\Console\Command;

class ChangeStockFree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:return-free';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Return temp stocks to free status and soft delete old temp stocks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeHold = now()->subMinutes(10);
        TempStock::where('created_at', '<', $timeHold)
            ->whereNull('deleted_at')
            ->chunkById(100, function ($tempStocks) {
                $productSerialIds = $tempStocks->pluck('product_serial_id');
                // return stock back to free again
                ProductSerial::whereIn('id', $productSerialIds)
                    ->update(['status' => ProductSerialType::getTypeFree()]);
                // make soft delete for this temp stock
                TempStock::whereIn('id', $tempStocks->pluck('id'))->delete();

            });

        $this->info('Completed return stock to free');
    }
}
