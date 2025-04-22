<?php

namespace App\Repositories\Brand;

use App\Helpers\FileUpload;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandRepository extends BaseRepository
{
    use FileUpload;

    private $brandTranslationRepository;
    private $languageRepository;
    private $brandImageRepository;
    private $categoryRepository;


    public function __construct(
        Application                                 $app ,
        BrandTranslationRepository                  $brandTranslationRepository,
        LanguageRepository                          $languageRepository,
        BrandImageRepository                        $brandImageRepository,
        CategoryRepository                          $categoryRepository
    )
    {
        parent::__construct($app);
        $this->brandTranslationRepository = $brandTranslationRepository;
        $this->brandImageRepository = $brandImageRepository;
        $this->languageRepository = $languageRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllBrands($requestData)
    {
        if (in_array($requestData->input('sort_by'), ['created_at', 'name', 'status']))
            $sortBy = $requestData->input('sort_by');
        else
            $sortBy = 'created_at';
        $sortDirection = $requestData->input('sort_direction', 'desc');
        $searchTerm = $requestData->input('search', '');
        $perPage = $requestData->input('per_page', PAGINATION_COUNT_ADMIN);
        $categoriesFilter = null;
        if ($requestData->has('categories_filter') && $requestData->input('categories_filter') != '') {
            $categoriesFilter = explode(',', $requestData->input('categories_filter', null));
        }
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;

        $query = $this->model->query();
        $query->leftJoin('brand_translations', function (JoinClause $join) use ($langId) {
            $join->on("brand_translations.brand_id", '=', "brands.id")
                ->where("brand_translations.language_id", $langId);
        });
        if (!empty($categoriesFilter)) {
            $allBrandIds = $this->categoryRepository->getBrandIdsForCategoryIdsAncestors($categoriesFilter);
            $query->whereIn('brands.id', $allBrandIds);
        }
        $query->select('brands.*');
        $query->groupBy([
            "id",
            "status",
            "image",
            "created_at",
            "updated_at",
        ]);
        $query->orderBy($sortBy == 'name' ? 'brand_translations.'.$sortBy : $sortBy, $sortDirection);
        // Apply searching
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
        return $query->with(['translations','images'])->paginate($perPage);

    }


    public function store($requestData)
    {
        $fileUrl = $this->save_file($requestData->image, 'brands');
        Log::info($requestData->image);
        $brand = $this->model->create([
            'status' => $requestData->status,
            'image' => $fileUrl,
        ]);
        if ($brand) {
            $this->brandTranslationRepository->storeOrUpdate($requestData, $brand->id);
            // $this->brandImageRepository->storeOrUpdate($requestData, $brand->id);
        }

        return $brand->load('translations', 'images');
    }


    public function show($brand_id)
    {
        $brand = $this->model->with(['translations', 'images'])->find($brand_id);
        if (!$brand) {
            return false;
        }
        $brand->has_subs = 0;
        if ($brand->categories()->count() > 0) {
            $brand->has_subs = 1;
        }

        return $brand;
    }

    public function show_details($brand_id)
    {
        $brand = $this->model->with(['translations', 'images', 'categories'])->find($brand_id);
        $brand->has_subs = 0;
        if ($brand->categories()->count() > 0) {
            $brand->has_subs = 1;
        }

        return $brand;
    }

    public function updateBrand($requestData, $brand_id)
    {
        $brand = $this->model->find($brand_id);
        if (!$brand) {
            return false;
        }
        $brand->status = $requestData->status;

        if ($requestData->hasFile('image')){
            $fileUrl = $this->save_file($requestData->image, 'brands');
            $brand->image = $fileUrl;
        }

        $brand->save();
        $this->brandTranslationRepository->storeOrUpdate($requestData, $brand->id);
        // $this->brandImageRepository->storeOrUpdate($requestData, $brand->id);
        return $brand->load('translations', 'images');
    }

    public function changeStatus($requestData, $id)
    {
        $brand = $this->model->where('id', $id)->first();
        if (!$brand)
            return false;
        $brand->status = $requestData->status;
        $brand->save();
        return $brand;
    }

    public function destroy($id)
    {
        $brand = $this->model->where('id', $id)->first();
        if (
            $brand->orderProducts()->count() > 0 ||
            // $brand->category_brands()->count() > 0 ||
            $brand->products()->count() > 0
        ) {
            return false;
        }
        return $brand->delete();
    }

    public function destroy_selected($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
        return true;
    }

    public function getBrandsByCategoryId($requestData, $category)
    {
        $searchTerm = $requestData->input('search', '');
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $subcategoryIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
        $subcategoryIds[] = $category->id;

        $query = $this->model->query();
        $query->leftJoin('brand_translations', function (JoinClause $join) use ($langId) {
            $join->on("brand_translations.brand_id", '=', "brands.id")
                ->where("brand_translations.language_id", $langId);
        })
            ->leftJoin('categories', "categories.brand_id", '=', "brands.id")
            ->whereIn("brands.id", $subcategoryIds)
            ->select([
                'brands.*',
                DB::raw("CASE
            WHEN EXISTS (
                SELECT 1
                FROM category_brands
                WHERE category_brands.brand_id = brands.id
                AND category_brands.category_id = {$category->id}
            ) THEN 1
            WHEN EXISTS (
                SELECT 1
                FROM categories
                WHERE categories.brand_id = brands.id
            ) THEN 1
            ELSE 0
        END as has_subs")
            ])
            ->groupBy([
                'brands.id',
                'brands.status',
                'brands.created_at',
                'brands.updated_at',
                'brands.image',
            ])
            ->with('images')
            ->where('brands.status', '=', 'active')
            ->where('brand_translations.name', 'like', '%' . $searchTerm . '%');

        $brands = $query->paginate(PAGINATION_COUNT_APP);

        // Handle placeholder images
        $brands->map(function ($item) {
            if ($item->images->isEmpty()) {
                $item->setRelation('images', collect([
                    (object)[
                        'brand_id' => $item->id,
                        'key' => 'logo',
                        'image' => asset('/storage/uploads/brands') . '/product-card-placeholder.png'
                    ]
                ]));
            }
            return $item;
        });

        return $brands;
    }


    /**
     * Brand Model
     *
     * @return string
     */
    public function model(): string
    {
        return Brand::class;
    }
}
