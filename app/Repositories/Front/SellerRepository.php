<?php

namespace App\Repositories\Front;

use App\Models\Seller\Seller;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class SellerRepository extends BaseRepository
{
    public function __construct(
        Application $app,
        private SellerAddressRepository $sellerAddressRepository,
        private SellerAttachmentRepository $sellerAttachmentRepository,
        private SellerTranslationRepository $sellerTranslationRepository,
    )
    {
        parent::__construct($app);
    }

    public function store($requestData)
    {
        return $this->model->create([
            'name' => $requestData->name,
            'owner_name' => $requestData->owner_name,
            'email' => $requestData->email,
            // 'phone' => $requestData->phone,
            'password' => bcrypt($requestData->password),
            'google2fa_secret' => $requestData->google2fa_secret,
        ]);
    }


    public function show($sellerId)
    {
        // get one seller
        $seller = $this->model->where('id', $sellerId)
            ->with([
                'admin:id,name,email',
                'sellerGroup',
                'sellerGroupLevel',
                'children',
                'sellerAddress.country',
                'sellerAddress.city',
                'sellerAddress.region',
                'sellerAttachment',
                'seller_transactions',
            ])
            ->withCount('children as sellers_count')
            ->first();

        return $seller;
    }



    public function model(): string
    {
        return Seller::class;
    }
}
