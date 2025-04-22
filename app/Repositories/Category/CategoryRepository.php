<?php

namespace App\Repositories\Category;

use App\Enums\GeneralStatusEnum;
use App\Models\Category\Category;
use App\Models\Category\CategoryBrand;
use App\Models\Language\Language;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository
{

    private $categoryTranslationRepository;
    private $languageRepository;

    public function __construct(Application $app, CategoryTranslationRepository $categoryTranslationRepository, LanguageRepository $languageRepository)
    {
        parent::__construct($app);

        $this->categoryTranslationRepository = $categoryTranslationRepository;
        $this->languageRepository = $languageRepository;

    }

    public function getAllCategories($requestData): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $sortFields = ['created_at', 'name', 'ancestor_name', 'web', 'mobile'];
        $sortBy = in_array($requestData->input('sort_by'), $sortFields) ? $requestData->input('sort_by') : 'ancestor_name';
        $sortDirection = $requestData->input('sort_direction', 'desc');
        $perPage = $requestData->input('per_page', PAGINATION_COUNT_ADMIN);
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $searchTerm = $requestData->input('search', '');
        $query = $this->model->query();
        $query->leftJoin('category_translations', function (JoinClause $join) use ($langId) {
            $join->on("category_translations.category_id", '=', "categories.id")
                ->where("category_translations.language_id", $langId);
        });
        $query->leftJoin('categories as ancestors', 'categories.parent_id', '=', 'ancestors.id')
            ->leftJoin('category_translations as ancestor_translations', function (JoinClause $join) use ($langId) {
                $join->on('ancestor_translations.category_id', '=', 'ancestors.id')
                    ->where('ancestor_translations.language_id', $langId);
            });
        $query->select('categories.*');
        $query->groupBy([
            "id",
            "parent_id",
            "brand_id",
            "image",
            "level",
            "status",
            "is_topup",
            "sort_order",
            "deleted_at",
            "created_at",
            "updated_at",
            "web",
            "mobile",
            "ancestor_translations.name",
            "category_translations.name"
        ]);
        // $query->whereNull('parent_id');
        $query->with(['translations','brand','ancestors']);
        // Apply sorting
        if ($sortBy == 'name') {
            $query->orderBy('category_translations.name', $sortDirection);
        } elseif ($sortBy == 'ancestor_name') {
            $query->orderBy('ancestor_translations.name', $sortDirection)
                ->orderBy('category_translations.name', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }
        // Apply search
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm, $langId) {
                $q->where('category_translations.name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('ancestors.translations', function ($ancestorQuery) use ($searchTerm, $langId) {
                        $ancestorQuery->where('name', 'like', '%' . $searchTerm . '%')
                            ->where('language_id', $langId);
                    });
            });
        }
        return $query->paginate($perPage);
    }

    public function trash()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function getAllCategoriesWithChild()
    {
        return $this->model->with('child')->whereNull('parent_id')->get();
    }

    public function getAllCategoriesForm()
    {
        $lang = Language::where('code', app()->getLocale())->first();
        return $this->model->leftJoin('category_translations', function ($join) {
            $join->on('categories.id', '=', 'category_translations.category_id');
        })->select('categories.*')->addSelect('category_translations.name')
            ->with(['child' => function ($query) use ($lang) {
                $query->leftJoin('category_translations', function ($join) {
                    $join->on('categories.id', '=', 'category_translations.category_id');
                })->select('categories.*')
                    ->addSelect('category_translations.name')
                    ->where('category_translations.language_id', $lang->id)
                    ->orderBy('category_translations.name');
            }])
            ->where('category_translations.language_id', $lang->id)
            ->orderBy('category_translations.name')
            ->paginate(PAGINATION_COUNT_ADMIN);

    }

    public function getBrandIdsForCategoryIdsAncestors(array $categoryIds): array
    {
        $brandIds = [];
        $categories = $this->model->whereIn('id', $categoryIds)->get();
        foreach ($categories as $category) {
            while ($category) {
                if ($category->brand_id) {
                    $brandIds[] = $category->brand_id;
                }
                if ($category->parent_id) {
                    $category = $category->parent;
                }else{
                    break;
                }
            }
        }

        return array_unique($brandIds);
    }

    public function store($data_request)
    {
        $data_request['status'] = GeneralStatusEnum::getStatusActive();
        $category = $this->model->create($data_request);
        if ($category)
        {
            $this->categoryTranslationRepository->storeOrUpdate($data_request, $category->id);
        }

        return $category;

    }

    public function update($data_request, $category_id)
    {
        $category = $this->model->find($category_id);
        $category->update($data_request);
        $this->categoryTranslationRepository->storeOrUpdate($data_request, $category->id);

        return $category;
    }

    public function update_status($data_request, $category_id)
    {
        $category = $this->model->find($category_id);
        $category->update($data_request);
        return $category;

    }

    public function show($id)
    {
        return $this->model->where('id', $id)->with([
            'translations',
            'brand',
            'parent'
        ])->first();
    }

    public function destroy($id)
    {
        $category = $this->model->where('id', $id)->first();
        if (
            $category->child()->count() > 0 ||
            $category->products()->count() > 0
        ){
            return false;
        }
        return $category->delete();
    }

    public function restore($id)
    {
        $categories = DB::table('categories')
            ->join('category_translations as cat1_trans', 'categories.id', '=', 'cat1_trans.category_id')
            ->where('categories.id', $id)
            ->whereNotNull('categories.deleted_at')
            ->whereNotIn('cat1_trans.name', function($query) use($id) {
                $query->select('cat2_trans.name')
                    ->from('category_translations as cat2_trans')
                    ->join('categories as cat2', 'cat2.id', '=', 'cat2_trans.category_id')
                    ->where('cat2_trans.category_id', '!=', $id)
                    ->whereNull('cat2.deleted_at');
            })
            ->get();

        if ($categories && count($categories) > 0)
            return $this->model->withTrashed()->find($id)->restore();
        else
            return false;
    }

    public function destroy_selected($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
        return true;
    }

    public function getMainCategories()
    {
        return $this->getModel()::with(['brand','parent'])
            ->withCount('child')
            ->whereNull('parent_id')
            ->active()
            ->get();
    }

    public function getSubCategories($requestData)
    {
        $parentId = $requestData->input('parent_id', null);
        $isBrands = $requestData->input('is_brands', 0);
        $searchTerm = $requestData->input('name', null);
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $subcategoriesQuery = $this->getModel()::query();
        $subcategoriesQuery->leftJoin('brands', "categories.brand_id", '=', "brands.id");
        $subcategoriesQuery->leftJoin('brand_translations', function ($join) use ($langId) {
            $join->on("brand_translations.brand_id", '=', "brands.id")->where("brand_translations.language_id", $langId);
        });

        if (! $parentId) {
            $subcategoriesQuery->whereNotNull('categories.brand_id');
        }
        elseif ($parentId && $isBrands) {
            $allSubcategoryIds = $this->getAllSubcategoryIds($parentId);
            $subcategoriesQuery->whereIn('categories.id', $allSubcategoryIds)
                ->whereNotNull('categories.brand_id');
        }
        else{
            $subcategoriesQuery->where('parent_id', $parentId);
        }

        $subcategoriesQuery->with(['brand','parent'])
            ->withCount('child')
            ->active();
        if ($searchTerm){
            $subcategoriesQuery->where('brand_translations.name','LIKE', "%{$searchTerm}%");
        }

        $subcategories = $subcategoriesQuery->paginate(PAGINATION_COUNT_APP);
        return $subcategories;
    }

    private function getAllSubcategoryIds($categoryId)
    {
        $category = $this->getModel()::find($categoryId);
        if (!$category) {
            return [];
        }
        $subcategories = $category->child;
        $allSubcategoryIds = $subcategories->pluck('id')->toArray();
        foreach ($subcategories as $subcategory) {
            $allSubcategoryIds = array_merge(
                $allSubcategoryIds,
                $this->getAllSubcategoryIds($subcategory->id)
            );
        }
        return $allSubcategoryIds;
    }

    private function formatCategoriesWithHasBrands(Collection $categories)
    {
        $categories->map(function ($item) {
            $item->has_brands = 0;
            $subcategories = Category::where('parent_id', $item->id)->withCount('brands')->get();
            if ($subcategories->contains(fn($sub) => $sub->brands_count > 0)) {
                $item->has_brands = 1;
            }
            elseif (count($item->child))
                $item->has_brands = 2;
            unset($item->child, $item->brands);
            return $item;
        });

        return $categories;
    }

    /**
     * Category Model
     *
     * @return string
     */
    public function model(): string
    {
        return Category::class;
    }

    public function getCategoryDetails($id)
    {
        return $this->model->query()
            ->select(['id', 'parent_id', 'image'])
            ->active()
            ->where('id', $id)
            ->first();
    }
}
