<?php

namespace App\Services\Pos;

use App\DTO\BaseDTO;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Pos\AuthResources\AuthResource;
use App\Http\Resources\Pos\CategoryResources\CategoryResource as MerchantCategoryResource;
use App\Interfaces\ServicesInterfaces\Merchant\HomeServiceInterface;
use App\Models\Currency\Currency;
use App\Models\Language\Language;
use App\Repositories\Category\CategoryRepository;
use App\Services\BaseService;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Support\Facades\Auth;

class HomeService  extends BaseService implements  HomeServiceInterface
{
    use ApiResponseAble;

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function home()
    {
        try {
            $main_categories = $this->categoryRepository->getMainCategories();
            $posInfo = Auth::guard('posApi')->user();
            $languages = Language::all() ?? [];
            $currencies = Currency::all() ?? [];

            $data = [
                'pos_info' => new AuthResource($posInfo),
                'languages' => LanguageResource::collection($languages),
                'currencies' => CurrencyResource::collection($currencies),
                'main_categories' => MerchantCategoryResource::collection($main_categories),
            ];
            return $this->ApiSuccessResponse($data);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }


    public function index(array $filter): mixed
    {
        // TODO: Implement index() method.
    }

    public function show($id): mixed
    {
        // TODO: Implement show() method.
    }

    public function store(BaseDTO $data): mixed
    {
        // TODO: Implement store() method.
    }

    public function update($id, BaseDTO $data): mixed
    {
        // TODO: Implement update() method.
    }

    public function delete($id): mixed
    {
        // TODO: Implement delete() method.
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }
}
