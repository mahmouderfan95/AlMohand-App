<?php

namespace App\Interfaces\IntegrationInterfaces;

interface StockServiceInterface
{
    public function checkStock(int $productId, int $quantity): bool;

}
