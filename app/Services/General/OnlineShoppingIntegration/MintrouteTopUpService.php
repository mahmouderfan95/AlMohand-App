<?php

namespace App\Services\General\OnlineShoppingIntegration;

use App\Repositories\Front\SettingRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class MintrouteTopUpService extends MintrouteVoucherService
{
    private $configration;

    public function __construct($configration)
    {
        $this->configration = $configration;
        parent::__construct($this->configration);
    }


    public function AccountInitialization($requestData)
    {
        try {
            $arrayData = [
                'username' => $this->configration->keys['username'],
                'password' => $this->configration->keys['password'],
                'data' => [
                    'denomination_id' => (int)$requestData['product_id'],
                ],
            ];

            Log::info($arrayData);

            $client = new Client([
                'headers' => $this->prepareHeaders($arrayData),
            ]);
            $response = $client->post($this->configration->keys['url_topup'] . 'account_initialization', [
                'json' => $arrayData,
            ]);

            $result = json_decode($response->getBody(), true);
            Log::info(json_encode($response));
            Log::info($response->getBody());
            Log::info($result);
            if ($result['status'])
                return $result['data'];
            else
                return false;

        } catch (Exception|GuzzleException $e) {
            Log::info($e);
            Log::info($e->getCode());
            return false;
            return $e->getMessage();
        }

    }

    public function AccountValidation($requestData)
    {
        try {
            $arrayData = [
                'username' => $this->configration->keys['username'],
                'password' => $this->configration->keys['password'],
                'data' => [
                    'denomination_id' => (int)$requestData['product_id'],
                ],
            ];
            foreach (array_slice($requestData, 1) as $key => $value){
                $arrayData['data'][$key] = $value;
            }

            Log::info($requestData);
            Log::info($arrayData);

            $client = new Client([
                'headers' => $this->prepareHeaders($arrayData),
            ]);
            $response = $client->post($this->configration->keys['url_topup'] . 'account_validation', [
                'json' => $arrayData,
            ]);

            $result = json_decode($response->getBody(), true);
            Log::info(json_encode($response));
            Log::info($response->getBody());
            Log::info($result);
            if ($result['status'])
                return $result['account_details'];
            else
                return false;

        } catch (Exception|GuzzleException $e) {
            Log::info($e);
            Log::info($e->getCode());
            return false;
            return $e->getMessage();
        }

    }

    public function AccountTopUp($requestData)
    {
        try {
            $order = ['topupTransaction' => [], 'quantity' => 0];
            for ($index = 1; $index <= 1; $index++) {   // $requestData['quantity']
                $time = time();
                $arrayData = [
                    'username' => $this->configration->keys['username'],
                    'password' => $this->configration->keys['password'],
                    'data' => [
                        'denomination_id' => (int)$requestData['product_id'],
                    ],
                ];
                foreach (array_slice($requestData, 2) as $key => $value){
                    $arrayData['data'][$key] = $value;
                }

                Log::info($requestData);
                Log::info($arrayData);

                $client = new Client([
                    'headers' => $this->prepareHeaders($arrayData),
                ]);
                $response = $client->post($this->configration->keys['url_topup'] . 'account_topup', [
                    'json' => $arrayData,
                ]);

                $result = json_decode($response->getBody(), true);
                Log::info(json_encode($response));
                Log::info($response->getBody());
                Log::info($result);
                if ($result['status']){
                    $order['topupTransaction'][] = [
                        'account_id' => $result['account_details']['account_id'],
                        'transaction_id' => $result['account_details']['transaction_id'],
                    ];
                    $order['quantity']++;
                }
            }

            return $order;
        } catch (Exception|GuzzleException $e) {
            Log::info($e);
            Log::info($e->getCode());
            return false;
        }

    }



}
