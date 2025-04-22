<?php

namespace App\Repositories\SalesRepUser;

use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Helpers\UniqueCodeGeneratorHelper;
use App\Models\BalanceRequest\BalanceRequest;
use App\Models\Distributor\Distributor;
use App\Models\Permission;
use App\Models\POSTerminal\PosTerminal;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Models\Role;
use App\Models\SalesRep\SalesRep;
use App\Models\SalesRep\SalesRepTransaction;
use App\Models\SalesRepLevel\SalesRepLevel;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\Language\LanguageRepository;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class SalesRepUserRepository extends BaseRepository
{

    private $salesRepUserLocationRepository;
    private $languageRepository;
    private $distributorPosTerminalRepository;

    public function __construct(Application                      $app, SalesRepUserLocationRepository $salesRepUserLocationRepository, LanguageRepository $languageRepository,
                                DistributorPosTerminalRepository $distributorPosTerminalRepository)
    {
        parent::__construct($app);

        $this->salesRepUserLocationRepository = $salesRepUserLocationRepository;
        $this->languageRepository = $languageRepository;
        $this->distributorPosTerminalRepository = $distributorPosTerminalRepository;

    }


    public function trash()
    {
        return $this->model->onlyTrashed()->get();
    }


    public function allBalanceRequests(Request $request)
    {
        return BalanceRequest::whereHas('distributor', function ($query) {
            $query->where('sales_rep_id', auth()->id());
        })
        ->when($request->from, function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->from);
        })
        ->when($request->to, function ($query) use ($request) {
            $query->whereDate('created_at', '<=', $request->to);
        })
        ->when($request->status, function ($query) use ($request) {
            if (in_array($request->status, [BalanceRequestStatusEnum::ACCEPTED, BalanceRequestStatusEnum::REJECTED])) {
                $query->where('status', $request->status);
            }
        })
        ->latest()
        ->paginate(PAGINATION_COUNT_ADMIN);
    }




    public function pendingBalanceRequests()
    {
        return BalanceRequest::where('status', '=', BalanceRequestStatusEnum::PENDING)->whereHas('distributor', function ($query) {
            $query->where('sales_rep_id', auth()->id());
        })->latest()->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function getAllSalesRepUsersForm($request)
    {
        $query = $this->model->with([
            'parent',
            'sales_rep_level',
            'sales_rep_locations',
            'children',
            'distributors' => function ($q) {
                $q->withCount('posTerminals');
            }
        ])->withCount('distributors');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('region_id')) {
            $regionId = $request->input('region_id');

            $query->whereHas('sales_rep_locations', function ($q) use ($regionId) {
                $q->where('region_id', $regionId);
            });
        }

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function transactions($request)
    {
        $userId = auth('salesRepApi')->user()->id;
        $transactions = collect();

        // Date filter conditions
        $from = $request->has('from') ? $request->from . ' 00:00:00' : null;
        $to = $request->has('to') ? $request->to . ' 23:59:59' : null;

        if (!$request->has('type') || $request->type === 'sales_rep') {
            $salesRepTransactions = SalesRepTransaction::where('approved_by', $userId)
                ->when($from, fn($query) => $query->where('created_at', '>=', $from))
                ->when($to, fn($query) => $query->where('created_at', '<=', $to))
                ->with('salesRep')
                ->get()
                ->map(function ($transaction) {
                    $transaction->type = 'sales_rep';
                    $transaction->amount = $transaction->balance_before - $transaction->balance_after;
                    return $transaction;
                });

            $transactions = $transactions->merge($salesRepTransactions);
        }

        if (!$request->has('type') || $request->type === 'pos') {
            $posTransactions = PosTerminalTransaction::whereNotNull('distributor_id')
                ->whereNotNull('pos_terminal_id')
                ->where('created_by', $userId)
                ->where('created_by_type', 'sales_rep')
                ->when($from, fn($query) => $query->where('transaction_date', '>=', $from))
                ->when($to, fn($query) => $query->where('transaction_date', '<=', $to))
                ->with('pos_terminal')
                ->get()
                ->map(function ($transaction) {
                    $transaction->type = 'pos';
                    return $transaction;
                });

            $transactions = $transactions->merge($posTransactions);
        }

        if (!$request->has('type') || $request->type === 'distributor') {
            $distributorTransactions = PosTerminalTransaction::whereNull('pos_terminal_id')
                ->where('created_by', $userId)
                ->where('created_by_type', 'sales_rep')
                ->when($from, fn($query) => $query->where('transaction_date', '>=', $from))
                ->when($to, fn($query) => $query->where('transaction_date', '<=', $to))
                ->with('distributor')
                ->get()
                ->map(function ($transaction) {
                    $transaction->type = 'distributor';
                    return $transaction;
                });

            $transactions = $transactions->merge($distributorTransactions);
        }

        return $transactions;
    }




    public function permissions($request)
    {
        $role = Role::where('name', 'SalesRep')->where('guard_name', 'salesRepApi')->first();

        if (!$role) {
            return response()->json(['message' => 'SalesRep role not found'], 404);
        }

        // Get all permissions assigned to salesRepApi guard
        $permissions = Permission::where('guard_name', 'salesRepApi')->get();

        // Sync permissions
        $role->syncPermissions($permissions);

        return $permissions->load('translations');
    }


    public function store($data_request)
    {
        $data_request['status'] = GeneralStatusEnum::getStatusActive();

        // Get sales rep level code
        $salesRepLevel = SalesRepLevel::find($data_request['sales_rep_level_id']);
        if (!$salesRepLevel) {
            throw new \Exception(__('Sales Rep Level not found'));
        }

        $levelCode = $salesRepLevel->code;

        // Get last sales rep with the same level
        $lastSalesRepUser = $this->model->where('sales_rep_level_id', $data_request['sales_rep_level_id'])
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSalesRepUser) {
            $lastCode = $lastSalesRepUser->code;
            preg_match('/-(\d+)$/', $lastCode, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $newCode = $levelCode . '-' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newCode = $levelCode . '-01';
        }

        // Add the generated code to the data request
        $data_request['code'] = $newCode;

        $salesRepUser = $this->model->create($data_request);
        if ($salesRepUser) {
            $this->salesRepUserLocationRepository->storeOrUpdate($data_request['locations'] ?? [], $salesRepUser->id);
        }

        return $salesRepUser;

    }

    public function update($data_request, $salesRepUserId)
    {
        $salesRepUser = $this->model->findOrFail($salesRepUserId);
        $data_request['code'] = $salesRepUser->code;
        $salesRepUser->update($data_request);

        if (isset($data_request['locations'])) {
            $this->salesRepUserLocationRepository->storeOrUpdate($data_request['locations'], $salesRepUser->id);
        }

        return $salesRepUser;
    }


    public function update_status($status, $salesRepUser_id)
    {
        $salesRepUser = $this->model->find($salesRepUser_id);
        $salesRepUser->status = $status;
        return $salesRepUser->save();

    }

    public function addTransaction(array $data_request, string $id)
    {
        $entity = $this->findEntity($data_request['type'], $id);

        if (!$entity) {
            return false;
        }

        $amount = (float)$data_request['amount'];
        $current_balance = (float)$entity->balance ?? 0;
        $authUser = auth('salesRepApi')->user();

        // Store balance before transaction
        $data_request['balance_before'] = $current_balance;

        // Calculate the new balance
        $new_balance = $this->calculateNewBalance($current_balance, $amount, $data_request['transaction_type']);

        if ($new_balance === false) {
            return false;
        }

        // Store balance after transaction
        $data_request['balance_after'] = $new_balance;

        DB::beginTransaction();
        try {
            // Update entity balance
            $entity->update(['balance' => $new_balance]);

            // Handle authenticated user's balance only for CREDIT transactions
            if ($data_request['transaction_type'] === 'credit' && $authUser->balance >= $amount) {
                $authUser->decrement('balance', $amount);
            }

            // Create transaction record
            $transaction = $this->createTransaction($entity, $data_request, $id, $data_request['type']);
            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }



    private function subtractFromAuthSalesRep(float $amount)
    {
        $salesRep = auth('salesRepApi')->user();

        if ($salesRep && $salesRep->balance >= $amount) {
            $salesRep->decrement('balance', $amount);
        }
    }

    /**
     * Find the correct entity based on type.
     */
    private function findEntity(string $type, string $id)
    {
        return match ($type) {
            'sales_rep' => $this->model->find($id),
            'merchant' => Distributor::find($id),
            'pos' => PosTerminal::find($id),
            default => null,
        };
    }

    /**
     * Calculate the new balance based on transaction type.
     */
    private function calculateNewBalance(float $current_balance, float $amount, string $transaction_type)
    {
        return match ($transaction_type) {
            'credit' => $current_balance + $amount,
            'debit' => ($current_balance >= $amount) ? $current_balance - $amount : false,
            default => false,
        };
    }

    /**
     * Create a new transaction record.
     */
    private function createTransaction($entity, array $data_request, string $id, string $type)
    {
        if ($type === 'sales_rep') {
            $data_request['approved_by'] = auth('salesRepApi')->user()->id;
            return $entity->transactions()->create($data_request);
        }
        return PosTerminalTransaction::create([
            'transaction_id' => Str::uuid(),
            'transaction_code' => UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code"),
            'distributor_id' => ($type === 'merchant') ? $id : $entity->distributorPosTerminal->distributor_id,
            'pos_terminal_id' => ($type === 'pos') ? $id : null,
            'amount' => $data_request['amount'],
            'balance_before' => $data_request['balance_before'],
            'balance_after' => $data_request['balance_after'],
            'currency_code' => $data_request['currency_code'] ?? 'USD',
            'type' => $data_request['transaction_type'],
            'track_id' => Str::uuid(),
            'status' => TransactionStatusEnum::SUCCESS,
            'payment_method' => $data_request['payment_method'] ?? null,
            'created_by' =>  auth('salesRepApi')->user()->id,
            'created_by_type' => 'sales_rep',
            'transaction_date' => now(),
        ]);
    }


    public function show($id)
    {
        return $this->model->where('id', $id)->with([
            'parent',
            'sales_rep_level',
            'sales_rep_locations',
            'distributors',
            'children.sales_rep_locations'
        ])->first();
    }


    public function posTerminalByMerchantID($distributor_id)
    {
        return $this->distributorPosTerminalRepository->getMerchantPosTerminals($distributor_id);
    }

    public function destroy($id)
    {
        $salesRepUser = $this->model->where('id', $id)->first();
        return $salesRepUser->delete();
    }


    public function destroy_selected($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
        return true;
    }

    public function getMainSalesRepUsers()
    {
        return $this->getModel()::with(['brand', 'parent'])
            ->withCount('child')
            ->whereNull('parent_id')
            ->active()
            ->get();
    }


    public function checkIfSalesRepActivated($username)
    {
        return $this->model->where('username', $username)->where('status', 'active')->first();
    }


    public function checkIfSalesRepAccount($username)
    {
        return $this->model->where('username', $username)->whereNotNull('password')->first();
    }


    public function register($data)
    {
        $sales_rep = $this->model->where('username', $data['username'])->first();
        $sales_rep->password = bcrypt($data['password']);
        return $sales_rep->save();
    }

    /**
     * SalesRepUser Model
     *
     * @return string
     */
    public function model(): string
    {
        return SalesRep::class;
    }

    public function getSalesRepUserDetails($id)
    {
        return $this->model->query()
            ->select(['id', 'parent_id', 'image'])
            ->active()
            ->where('id', $id)
            ->first();
    }
}
