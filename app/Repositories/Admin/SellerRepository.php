<?php

namespace App\Repositories\Admin;

use App\Enums\SellerApprovalType;
use App\Models\Language\Language;
use App\Models\Seller\Seller;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Query\JoinClause;
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

    public function index($requestData)
    {
        $query = $this->getSellers($requestData);
        // Retrieve paginated results ( just approved )
        return $query->where('approval_status', SellerApprovalType::getTypeApproved())
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function notApprovedSellers($requestData)
    {
        $query = $this->getSellers($requestData);
        // Retrieve paginated results ( just approved )
        return $query->where('approval_status', '<>', SellerApprovalType::getTypeApproved())
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function getSellers($requestData)
    {
        // get current lang id
        $lang = Language::where('code', app()->getLocale())->first();
        // Get query parameters for sorting and searching
        // Default to sorting by created_at
        if(in_array($requestData->input('sort_by'), ['created_at', 'name', 'owner_name', 'seller_group', 'seller_level', 'balance', 'status']))
            $sortBy = $requestData->input('sort_by');
        else
            $sortBy = 'created_at';
        // Default to descending order
        $sortDirection = $requestData->input('sort_direction', 'desc');
        // Default to search value
        $searchTerm = $requestData->input('search', '');
        // Default to seller Group value
        $sellerGroupFilter = null;
        if ($requestData->has('seller_group_filter') && $requestData->input('seller_group_filter') != '') {
            $sellerGroupFilter = explode(',', $requestData->input('seller_group_filter', null));
        }
        // Default to admin ids
        $adminIds = null;
        if ($requestData->has('admin_ids') && $requestData->input('admin_ids') != '') {
            $adminIds = explode(',', $requestData->input('admin_ids', null));
        }
        // Default to seller Level value
        $sellerLevelFilter = null;
        if ($requestData->has('seller_level_filter') && $requestData->input('seller_level_filter') != '') {
            $sellerLevelFilter = explode(',', $requestData->input('seller_level_filter', null));
        }
        // Default to status value
        $statusFilter = $requestData->input('status_filter', null);
        // Default to dates filter
        $startDate = $requestData->input('start_date', null);
        $endDate = $requestData->input('end_date', null);
        // add one day for endDate to use whereBetween
        $endDate = Carbon::parse($endDate);
        $endDate->addDay();

        // Build the base query
        $query = $this->model->query();
        // Join attached table
        $query->leftJoin('seller_groups', function (JoinClause $join) use ($sellerGroupFilter) {
            $join->on("sellers.seller_group_id", '=', "seller_groups.id");
            // Apply filter if provided
            if (!empty($sellerGroupFilter)) {
                $join->whereIn('sellers.seller_group_id', $sellerGroupFilter);
            }
        });
        $query->leftJoin('seller_group_levels', function (JoinClause $join) use ($sellerLevelFilter) {
            $join->on("sellers.seller_group_level_id", '=', "seller_group_levels.id");
            // Apply filter by sellerLevel id if provided
            if (!empty($sellerLevelFilter)) {
                $join->whereIn('sellers.seller_group_level_id', $sellerLevelFilter);
            }
        });
        $query->leftJoin('seller_group_translations', function (JoinClause $join) use ($lang) {
            $join->on("seller_group_translations.seller_group_id", '=', "seller_groups.id")
                ->where("seller_group_translations.language_id", $lang->id);
        });
        $query->leftJoin('seller_group_level_translations', function (JoinClause $join) use ($lang) {
            $join->on("seller_group_level_translations.seller_group_level_id", '=', "seller_group_levels.id")
                ->where("seller_group_level_translations.language_id", $lang->id);
        });
        $query->join('users', function (JoinClause $join) use ($adminIds) {
            $join->on("sellers.user_id", '=', "users.id");
            // Apply filter by admin id if provided
            if (!empty($adminIds)) {
                $join->whereIn('sellers.user_id', $adminIds);
            } else {
                // Include sellers with user_id as null
                $join->orWhereNull('sellers.user_id');
            }
        });
        $query->select(
            'sellers.id',
            'sellers.user_id',
            'sellers.name',
            'sellers.owner_name',
            'sellers.email',
            'sellers.logo',
            'sellers.approval_status',
            'sellers.balance',
            'sellers.status',
            'sellers.created_at',
            'sellers.updated_at',
            'sellers.seller_group_id',
            'sellers.seller_group_level_id',
        );
        $query->groupBy(
            'sellers.id',
            'sellers.user_id',
            'sellers.name',
            'sellers.owner_name',
            'sellers.email',
            'sellers.logo',
            'sellers.approval_status',
            'sellers.balance',
            'sellers.status',
            'sellers.created_at',
            'sellers.updated_at',
            'sellers.seller_group_id',
            'sellers.seller_group_level_id',
        );
        // get attaching with product
        $query->with([
            'admin:id,name,email',
            'sellerGroup',
            'sellerGroupLevel'
        ]);

        // Apply sorting
        if ($sortBy == 'seller_group')
            $query->orderBy('seller_group_translations.name', $sortDirection);
        elseif ($sortBy == 'seller_level')
            $query->orderBy('seller_group_level_translations.name', $sortDirection);
        else
            $query->orderBy($sortBy, $sortDirection);

        // Apply date filter
        if($startDate && $endDate)
            $query->whereBetween('sellers.created_at', [$startDate, $endDate]);
        // Apply status filter
        if(in_array($statusFilter, ['active','inactive']))
            $query->where('sellers.status', $statusFilter);

        // Apply searching
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('sellers.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sellers.owner_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Retrieve query
        return $query;
    }

    public function store($requestData)
    {
        // store new row with main details
        $seller = $this->model->create([
            'user_id' => $requestData->user_id ?? null,
            'parent_id' => null, // $requestData->parent_id ?? null,
            'name' => $requestData->name,
            'owner_name' => $requestData->owner_name,
            'email' => $requestData->email,
            'phone' => $requestData->phone,
            'password' => bcrypt($requestData->password),
            'status' => $requestData->status,
            'approval_status' => $requestData->approval_status,
            'logo' => $requestData->logo_url,
            'address_details' => $requestData->address_details,
            'seller_group_id' => $requestData->seller_group_id ?? null,
            // 'seller_group_level_id' => $requestData->seller_group_level_id,
        ]);
        // store new values of seller ( address and attachment )
        if ($seller) {
            $this->sellerAddressRepository->store($requestData, $seller->id);
            $this->sellerAttachmentRepository->store($requestData, $seller->id);
        }
        return $seller;
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

    public function showSimple($sellerId)
    {
        return $this->model->where('id', $sellerId)->first();
    }

    public function updateSeller($requestData, Seller $seller)
    {
        // update main table
        $seller->update([
            'parent_id' => $requestData->parent_id ?? null,
            'name' => $requestData->name,
            'owner_name' => $requestData->owner_name,
            'email' => $requestData->email,
            'phone' => $requestData->phone,
            'logo' => $requestData->logo_url,
            'address_details' => $requestData->address_details,
            'seller_group_id' => $requestData->seller_group_id,
            'seller_group_level_id' => $requestData->seller_group_level_id,
        ]);
        // delete old address
        $sellerAddress = $this->sellerAddressRepository->deleteBySellerId($seller->id);
        $sellerAttachment = $this->sellerAttachmentRepository->deleteBySellerId($seller->id);
        // store new values for seller address
        if ($sellerAddress && $sellerAttachment){
            $this->sellerAddressRepository->store($requestData, $seller->id);
            $this->sellerAttachmentRepository->store($requestData, $seller->id);
        }

        return true;
    }

    public function changeStatus($requestData, $sellerId)
    {
        // find seller with id
        $seller = $this->model->find($sellerId);
        if(!$seller){
            return false;
        }
        // change status
        $seller->status = $requestData->status;
        $seller->save();

        return $seller;
    }
   /* public function add_balance($balance, $sellerId)
    {
        // find seller with id
        $seller = Seller::where('id',$sellerId)->first();
        if(!$seller)
            return false; dd($seller['id']);
        if(!$seller){
            return false;
        }


        // change status
        $seller->balance =  $seller->balance + $balance;
        $seller->save();

        return $seller;
    }*/

    public function changeApprovalStatus($requestData, $sellerId)
    {
        // find seller with id
        $seller = $this->model->find($sellerId);
        if(!$seller)
            return false;
        // check if approve him or not InCondition( if its status is pending or complete profile )
        if (
            in_array($seller->approval_status, [SellerApprovalType::getTypePending(), SellerApprovalType::getTypeCompleteProfile()])
        ){
            // change its status to approved or reject
            $seller->approval_status = $requestData->approval_status;
            // check if approval status rejected, store reason
            if($requestData->approval_status == SellerApprovalType::getTypeRejected())
                $this->sellerTranslationRepository->store($requestData, $sellerId);
            else
                $seller->user_id = $requestData->user_id;
        }
        $seller->save();
        return $seller;
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function trash()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore($id)
    {
        return $this->model->withTrashed()->find($id)->restore();

    }


    public function model(): string
    {
        return Seller::class;
    }
}
