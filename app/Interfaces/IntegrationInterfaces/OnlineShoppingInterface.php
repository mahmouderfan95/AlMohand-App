<?php

namespace App\Interfaces\IntegrationInterfaces;

interface OnlineShoppingInterface
{
    public function purchaseProduct($requestData);
    public function orderDetails($requestData);
    public function orders($requestData);
}
